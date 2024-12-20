<?php

namespace App\Services\Admin;

use App\Jobs\OrderTracking;
use App\Models\DeliveryCharge;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderLog;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use niklasravnsborg\LaravelPdf\Facades\Pdf;

/**
 * Class OrderService.
 */
class OrderService
{
    public function orderStatusList()
    {
        $status = [
            ["status" => 1, "title" => "Approved By Business"],
            ["status" => 3, "title" => "Approved By Tellecto"],
            ["status" => 4, "title" => "Declined By Tellecto"],
            ["status" => 7, "title" => "Delivered"]
        ];

        return response()->json([
            'status' => 'success',
            'data' => $status
        ]);
    }


    public function orderList()
    {
        $data = Order::query();
        $data->adminOrder();
        $data->when(request()->get('agent_id'), function ($query) {
            $query->where('agent_id', request()->get('agent_id'));
        });
        $data->when(request()->get('business_id'), function ($query) {
            $query->where('business_id', request()->get('business_id'));
        });

        if(request()->has('order_status')) {
            $data->where('order_status',request()->get('order_status'));
        }
        $data->when(request()->get('tellecto_order_no'), function ($query) {
            $query->where('tellecto_order_no', request()->get('tellecto_order_no'));
        });
        $data->when(request()->get('dcs_order_no'), function ($query) {
            $query->where('dcs_order_no', request()->get('dcs_order_no'));
        });
        $data->when(request()->get('invoice_no'), function ($query) {
            $query->where('invoice_no', request()->get('invoice_no'));
        });
        $data->when(request()->get('requsition'), function ($query) {
            $query->where('requsition', request()->get('requsition'));
        });
        $data->when(request()->get('tracking_number'), function ($query) {
            $query->where('tracking_number', request()->get('tracking_number'));
        });
        $data->when(request()->get('search'), function ($query) {
            $search = request()->get('search');
            $query->where('delivery_name', "LIKE", "%{$search}%");
            $query->orWhere('requsition', $search);
            $query->orWhere('delivery_phone', $search);
            $query->orWhere('delivery_mobile', $search);
            $query->orWhere('tellecto_order_no', $search);
            $query->orWhere('dcs_order_no', $search);
            $query->orWhere('invoice_no', $search);
            $query->orWhere('tracking_number', $search);
        });
        $data->with([
            'order_items' => function ($item) {
                //$item->adminSelectedFields();
                $item->with([
                    'product' => function ($product) {
                        $product->select('id', 'product_number', 'product_name');
                    }
                ]);
            }
        ]);
        //$data->adminSelectedFields();
        $data->orderBy('tellecto_order_no','desc');
        return paginationResponse('success', 200, $data, request('showPerPage'));
    }

    public function orderInfo($order_id)
    {
        $data = Order::with([
            'order_items' => function ($item) {
                //$item->adminSelectedFields();
                $item->with([
                    'product' => function ($product) {
                        $product->select('id', 'product_number', 'product_name');
                    }
                ]);
            }
        ])->where('id', $order_id)->first();
        return $data;
    }

    public function orderDetails($order_id)
    {
        $data = $this->orderInfo($order_id);
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function changeDeliveryOption($request, $order_id)
    {
        // Delivery information
        $order = Order::findOrFail($order_id);

        $delivery_option = DeliveryCharge::findOrFail($request->delivery_id);
        $delivery_charge = round($delivery_option->delivery_charge, 2);
        $order->delivery_id = $delivery_option->id;
        $order->delivery_code = $delivery_option->code;
        $order->delivery_type = $delivery_option->delivery_type;

        $dcs_delivery_charge = $delivery_option->dcs_charge;
        $order->dcs_delivery_charge = $dcs_delivery_charge;
        $order->delivery_charge = $delivery_charge;
        $delivery_charge_vat_rate = env('SHIPPING_VAT_RATE') ?? 25;
        $order->delivery_charge_vat_rate = $delivery_charge_vat_rate;
        $delivery_charge_vat = $delivery_charge * $delivery_charge_vat_rate / 100;
        $delivery_charge_with_vat = $delivery_charge + $delivery_charge_vat;
        $order->delivery_charge_with_vat = $delivery_charge_with_vat;

        // Admin Calculation
        $total_excluding_vat_admin = $order->total_excluding_vat_admin;
        $total_vat_admin = $order->total_vat_admin;
        $total_including_vat_admin = $order->total_excluding_vat_admin + $total_vat_admin;
        $total_order_amount_admin = $total_including_vat_admin + $dcs_delivery_charge;

        $order->total_excluding_vat_admin = $total_excluding_vat_admin + $dcs_delivery_charge;
        $order->total_vat_admin = $total_vat_admin;
        $order->total_including_vat_admin = $total_including_vat_admin;
        $order->total_order_amount_admin = $total_order_amount_admin;

        // Business Calculation
        $total_excluding_vat_business = $order->total_excluding_vat_business;
        $total_vat_business = $order->total_vat_business;
        $total_including_vat_business = $total_excluding_vat_business + $total_vat_business;
        $total_order_amount_business = $total_including_vat_business + $delivery_charge_with_vat;

        $order->total_excluding_vat_business = $total_excluding_vat_business + $delivery_charge;
        $order->total_vat_business = $total_vat_business + $delivery_charge_vat;
        $order->total_including_vat_business = $total_including_vat_business + $total_vat_business;
        $order->total_order_amount_business = $total_order_amount_business;

        // Agent Calculation
        $total_excluding_vat_agent = $order->total_excluding_vat_agent;
        $total_vat_agent = $order->total_vat_agent;
        $total_including_vat_agent = $total_excluding_vat_agent + $total_vat_agent;
        $total_order_amount_agent = $total_including_vat_agent + $delivery_charge_with_vat;

        $order->total_excluding_vat_agent = $total_excluding_vat_agent + $delivery_charge;
        $order->total_vat_agent = $total_vat_agent + $delivery_charge_vat;
        $order->total_including_vat_agent = $total_including_vat_agent;
        $order->total_order_amount_agent = $total_order_amount_agent;

        $order->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Shipping method has been changed successfully!'
        ]);
    }

    public function approveOrder($order_id)
    {
        if (Order::where('id', $order_id)->confirmed()->exists()) {
            // Submit order to dcs
            $dcs_response = Order::placedOrderToDCS($order_id);
            //return $dcs_response;
            if (isset($dcs_response["orderNumber"])) {
                $order = Order::findOrFail($order_id);
                $order->order_status = 3;
                $order->dcs_order_no = $dcs_response["orderNumber"];
                $order->approved_by = Auth::id();
                $order->save();
                OrderLog::saveOrderLog($order_id, authUserId(), 3); // Save order log

                // Send mail to agent
                $agent_mail_data = [
                    'activity_type' => 'order_approved_by_tellecto_to_agent',
                    'view_file' => 'mail.order.layouts',
                    'to_email' => $order->customer_email,
                    'to_name' => $order->customer_name,
                    'subject' => 'Order #'.$order->tellecto_order_no.' Approved – by TELLECTO',
                    'order' => $order
                ];
                sendMail($agent_mail_data);

                // Send mail to business
                $business_mail_data = [
                    'activity_type' => 'order_approved_by_tellecto_to_business',
                    'view_file' => 'mail.order.layouts',
                    'to_email' => $order->business->email,
                    'to_name' => $order->business->name,
                    'subject' => 'Order #'.$order->tellecto_order_no.' Approved – by TELLECTO',
                    'order' => $order
                ];
                sendMail($business_mail_data);

                return response()->json([
                    'status' => 'success',
                    'dcs_order_no' => $dcs_response['orderNumber'],
                    'message' => 'You order has been placed successfully to dcs'
                ]);
            } else {
                return failedResponse("Couldn't submit order [" . $dcs_response['code'] . "] (" . $dcs_response["errorTekst"] . ")");
                //throw new \Exception("Couldn't submit order [" . $response['code'] . "] (" . $response["errorTekst"] . ")");
            }

        } else {
            return failedResponse("Invalid order ID");
        }
    }

    public function declineOrder($order_id)
    {
        if (Order::where('id', $order_id)->confirmed()->exists()) {
            $order = Order::findOrFail($order_id);
            $order->order_status = 4;
            $order->rejected_by = Auth::id();
            $order->rejected_for = request()->get('rejected_for') ?? null;
            $order->save();
            foreach ($order->order_items as $item) {
                $product_info = Product::find($item->product_id);
                $current_inventory = $product_info->inventory + $item->quantity;
                $product_info->inventory = $current_inventory;
                $product_info->save();
                if ($current_inventory > 0) {
                    Product::find($item->product_id)->update(['stock_status' => 'YES']);
                }
            }
            OrderLog::saveOrderLog($order_id, authUserId(), 4);

            // Send mail to agent
            $agent_mail_data = [
                'activity_type' => 'order_reject_by_tellecto_to_agent',
                'view_file' => 'mail.order.layouts',
                'to_email' => $order->customer_email,
                'to_name' => $order->customer_name,
                'subject' => 'Order #' . $order->tellecto_order_no . ' Cancelled by TELLECTO',
                'order' => $order
            ];
            sendMail($agent_mail_data);
            // Send mail to business
            $business_mail_data = [
                'activity_type' => 'order_rejected_by_tellecto_to_business',
                'view_file' => 'mail.order.layouts',
                'to_email' => $order->business->email,
                'to_name' => $order->business->name,
                'subject' => 'Order #' . $order->tellecto_order_no . ' Cancelled by TELLECTO',
                'order' => $order
            ];
            sendMail($business_mail_data);
            return response()->json([
                'status' => 'success',
                'message' => 'Information has been updated successfully!'
            ]);
        } else {
            return failedResponse("Invalid order ID");
        }
    }

    public function orderLogs($order_id)
    {
        return Order::getOrderLogAttribute($order_id);
    }

    public function reserveOrder()
    {
        $orders = Order::latest()->get();
        foreach ($orders as $order) {
            return Order::reserveorder($order->id);
        }
    }

    public function trackOrder()
    {
        return $tracking_info = Order::trackOrder(68);
        OrderTracking::dispatch();
        return response()->json([
            'status' => 'success',
            'message' => 'Order tracking job started successfully.You will get a notification after complete!'
        ]);
    }

    public function downloadAdminInvoice($order_id, $invoice_type)
    {
        $data = Order::adminSelectedFields()->with([
            'business' => function ($query) {
                $query->select('id', 'name', 'email', 'mobile');
                $query->with([
                    'user_information' => function ($info) {
                        $info->businessSelectedFields();
                    }
                ]);
            },
            'agent' => function ($query) {
                $query->select('id', 'first_name', 'last_name', 'phone', 'email');
            },
            'order_items' => function ($item) {
                //$item->adminSelectedFields();
                $item->with([
                    'product' => function ($product) {
                        $product->select('id', 'product_number', 'product_name');
                    }
                ]);
            }
        ])->where('id', $order_id)->firstOrFail(); //->invoice() scope should add
        if ($invoice_type === "purchase") {
            $file = PDF::loadView('pdf.admin_invoice', compact('data'));
        } elseif ($invoice_type === "order") {
            $file = PDF::loadView('pdf.business_invoice', compact('data'));
        }
        // Temporary saved on a directory
//        $folderPath = "assets/files/invoice/";
//        $fileName = "tellecto_invoice_" . $data->tellecto_order_no . ".pdf";
//        File::isDirectory($folderPath) || File::makeDirectory($folderPath, 0777, true, true);
//        $file->save($folderPath . $fileName);
//
//        return response()->json([
//            'status' => 'success',
//            'data' => $folderPath . $fileName
//        ]);
        // Temporary
        return $file->download("invoice_".$data->tellecto_order_no . '.pdf');
    }

    public function downloadBusinessInvoice($order_id)
    {
        $data = Order::businessSelectedFields()->with([
            'business' => function ($query) {
                $query->select('id', 'name', 'email', 'mobile');
                $query->with([
                    'user_information' => function ($info) {
                        $info->businessSelectedFields();
                    }
                ]);
            },
            'agent' => function ($query) {
                $query->select('id', 'first_name', 'last_name', 'phone', 'email');
            },
            'order_items' => function ($item) {
                $item->businessSelectedFields();
                $item->with([
                    'product' => function ($product) {
                        $product->select('id', 'product_number', 'product_name');
                    }
                ]);
            }
        ])->where('id', $order_id)->firstOrFail();
        $file = PDF::loadView('pdf.business_invoice', compact('data'));
        // Temporary saved on a directory
//        $folderPath = "assets/files/invoice/";
//        $fileName = "business_invoice_" . $data->tellecto_order_no . ".pdf";
//        File::isDirectory($folderPath) || File::makeDirectory($folderPath, 0777, true, true);
//        $file->save($folderPath . $fileName);
//
//        return response()->json([
//            'status' => 'success',
//            'data' => $folderPath . $fileName
//        ]);
        // Temporary
        return $file->download("invoice_".$data->tellecto_order_no . '.pdf');
    }

    public function downloadAgentInvoice($order_id)
    {
        $data = Order::agentSelectedFields()->with([
            'business' => function ($query) {
                $query->select('id', 'name', 'email', 'mobile');
                $query->with([
                    'user_information' => function ($info) {
                        $info->businessSelectedFields();
                    }
                ]);
            },
            'agent' => function ($query) {
                $query->select('id', 'first_name', 'last_name', 'phone', 'email');
            },
            'order_items' => function ($item) {
                $item->agentSelectedFields();
                $item->with([
                    'product' => function ($product) {
                        $product->select('id', 'product_number', 'product_name');
                    }
                ]);
            }
        ])->where('id', $order_id)->firstOrFail();
        $file = PDF::loadView('pdf.agent_invoice', compact('data'));
        // Temporary saved on a directory
        $folderPath = "assets/files/invoice/";
        $fileName = "agent_invoice_" . $data->tellecto_order_no . ".pdf";
        File::isDirectory($folderPath) || File::makeDirectory($folderPath, 0777, true, true);
        $file->save($folderPath . $fileName);

        return response()->json([
            'status' => 'success',
            'data' => $folderPath . $fileName
        ]);
        // Temporary
        return $file->download($data->invoice_no ?? $data->tellecto_order_no . '.pdf');
    }

    public function shippingMethods()
    {

        $data = DeliveryCharge::query();
        $data->where('status', 1);
        if (request()->has('order_id')) {
            $order_id = request()->get('order_id');
            $products = OrderItem::whereIn('order_id',[$order_id])->pluck('product_id');
            $orderProductWeight = Product::whereIn('id',$products)->sum('weight');
            $data->where('max_weight', '>=', $orderProductWeight);
        }
        $data->select('id', 'code', 'delivery_type', 'delivery_charge', 'vat_rate', 'max_weight');
        $delivery_charges = $data->get();
        return response()->json([
            'status' => 'success',
            'data' => $delivery_charges
        ]);
    }
}
