<?php

namespace App\Services\Business;

use App\Models\Order;
use App\Models\OrderLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use niklasravnsborg\LaravelPdf\Facades\Pdf;

/**
 * Class OrderService.
 */
class OrderService
{
    public function orderList()
    {
        $data = Order::query();
        $data->where("business_id", authUserId());
        if (request()->has('order_status')) {
            $data->where('order_status', request()->get('order_status'));
        }
        $data->when(request()->get('agent_id'), function ($query) {
            $query->where('agent_id', request()->get('agent_id'));
        });
        $data->when(request()->get('tellecto_order_no'), function ($query) {
            $query->where('tellecto_order_no', request()->get('tellecto_order_no'));
        });

        $data->when(request()->get('requsition'), function ($query) {
            $query->where('requsition', request()->get('requsition'));
        });

        $data->when(request()->get('search'), function ($query) {
            $search = request()->get('search');
            $query->where('delivery_name', "LIKE", "%{$search}%");
            $query->orWhere('requsition', $search);
            $query->orWhere('delivery_phone', $search);
            $query->orWhere('delivery_mobile', $search);
            $query->orWhere('tellecto_order_no', $search);
            $query->orWhere('invoice_no', $search);
            $query->orWhere('tracking_number', $search);
        });
        $data->businessSelectedFields();
        $data->orderBy('tellecto_order_no', 'desc');
        $data->with([
            'order_items' => function ($item) {
                $item->businessSelectedFields();
                $item->with([
                    'product' => function ($product) {
                        $product->select('id', 'product_number', 'product_name');
                    }
                ]);
            }
        ]);

        return paginationResponse('success', 200, $data, request('showPerPage'));
    }

    protected function orderInfo($order_id)
    {
        $data = Order::with([
            'order_items' => function ($item) {
                $item->businessSelectedFields();
                $item->with([
                    'product' => function ($product) {
                        $product->select('id', 'product_number', 'product_name');
                    }
                ]);
            }
        ])->where('id', $order_id)->where('business_id', authUserId())->businessSelectedFields()->first();
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

    public function confirmOrder($order_id)
    {
        if (Order::where('id', $order_id)->where('business_id', authUserId())->pending()->exists()) {
            $order = Order::findOrFail($order_id);
            $order->order_status = 1;
            $order->confirmed_by = Auth::id();
            $order->save();
            OrderLog::saveOrderLog($order_id, authUserId(), 1);
            // Save invoice for mail attachment
            Order::saveBusinessInvoice($order->id);
            // Send mail to tellecto (attachemt pabe tar sale price ar opor)
            $tellecto_mail_data = [
                'activity_type' => 'order_confirmed_by_business_to_admin',
                'view_file' => 'mail.order.layouts',
                'to_email' => 'info@tellecto.se',
                'to_name' => "Tellecto Admin",
                'subject' => 'Order #' . $order->tellecto_order_no . ' Approved by Business Owner – Action Required',
                'order' => $order,
                'attachment_path' => "assets/files/invoice/" . "business_invoice_" . $order->tellecto_order_no . ".pdf",
                'attachment_name' => 'order_' . $order->tellecto_order_no . '_invoice' . '.pdf',
                'attachment_mime' => 'application/pdf'
            ];
            sendMail($tellecto_mail_data);
            // Send mail to agent
            $agent_mail_data = [
                'activity_type' => 'order_confirmed_by_business_to_agent',
                'view_file' => 'mail.order.layouts',
                'to_email' => $order->customer_email,
                'to_name' => $order->customer_name,
                'subject' => 'Order #' . $order->tellecto_order_no . ' – Confirmed by ' . $order->business->user_information->company_name,
                'order' => $order
            ];
            sendMail($agent_mail_data);
            // Send mail to business with invoice (attachemt pabe tar purchase price ar opor)
            $business_mail_data = [
                'activity_type' => 'order_confirmed_by_business_to_business',
                'view_file' => 'mail.order.layouts',
                'to_email' => $order->business->user_information->company_email,
                'to_name' => $order->business->name,
                'subject' => 'Order #' . $order->tellecto_order_no . ' – Confirmation notification',
                'order' => $order,
                'attachment_path' => "assets/files/invoice/" . "business_invoice_" . $order->tellecto_order_no . ".pdf",
                'attachment_name' => 'order_' . $order->tellecto_order_no . '_invoice' . '.pdf',
                'attachment_mime' => 'application/pdf'
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

    public function cancelOrder($order_id)
    {
        if (Order::where('id', $order_id)->where('business_id', authUserId())->pendingOrConfirmed()->exists()) {
            $order = Order::findOrFail($order_id);
            $order->order_status = 2;
            $order->canceled_by = Auth::id();
            $order->canceled_for = request()->get('canceled_for') ?? null;
            $order->save();
            OrderLog::saveOrderLog($order_id, authUserId(), 2);

            $mail_data = [
                'activity_type' => 'order_canceled_by_business_to_agent',
                'view_file' => 'mail.order.layouts',
                'to_email' => $order->customer_email,
                'to_name' => $order->customer_name,
                'subject' => 'Order #' . $order->tellecto_order_no . ' Cancelled by ' . $order->business->user_information->company_name ?? '-',
                'order' => $order
            ];
            sendMail($mail_data);

            return response()->json([
                'status' => 'success',
                'message' => 'Information has been updated successfully!'
            ]);
        } else {
            return failedResponse("Invalid order ID");
        }
    }

    public function downloadInvoice($order_id, $invoice_type)
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
        ])->where('id', $order_id)->where('business_id', authUserId())->invoice()->firstOrFail();
        if ($invoice_type === "purchase") {
            $file = PDF::loadView('pdf.business_invoice', compact('data'));
        } elseif ($invoice_type === "order") {
            $file = PDF::loadView('pdf.agent_invoice', compact('data'));
        }
        //return $file->download();
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

        return $file->download("invoice_" . $data->tellecto_order_no . '.pdf');
    }

    public function orderStatusList()
    {
        $status = [
            ["status" => 0, "title" => "Pending"],
            ["status" => 1, "title" => "Approved By Business"],
            ["status" => 2, "title" => "Declined by Business"],
            ["status" => 3, "title" => "Approved By Tellecto"],
            ["status" => 4, "title" => "Declined By Tellecto"],
            ["status" => 7, "title" => "Delivered"]
        ];

        return response()->json([
            'status' => 'success',
            'data' => $status
        ]);
    }
}
