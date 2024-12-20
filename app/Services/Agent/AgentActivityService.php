<?php

namespace App\Services\Agent;

use App\Models\Agent;
use App\Models\BusinessProductPrice;
use App\Models\DeliveryCharge;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderLog;
use App\Models\OrderParcelShop;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use niklasravnsborg\LaravelPdf\Facades\Pdf;

/**
 * Class AgentActivityService.
 */
class AgentActivityService
{
    public function agentProduct()
    {
        $business_id = auth()->guard('agent')->user()->business_id;
        $data = BusinessProductPrice::with([
            'product' => function ($product) {
                $product->when(request()->get('category_id'), function ($query) {
                    $query->where('category_id', request()->get('category_id'));
                });
                $product->with([
                    'category' => function ($query) {
                        $query->select('id', 'name', 'slug');
                    },
                    'subcategory' => function ($query) {
                        $query->select('id', 'name', 'slug');
                    },
                    'brand' => function ($query) {
                        $query->select('id', 'name', 'slug');
                    },
                    'images' => function ($query) {
                        $query->select('id', 'product_id', 'image');
                    }
                ]);
                $product->agentSelectedFields();
            }
        ])->where('business_id', $business_id)
            ->select('id', 'product_id', 'business_id', 'sale_price');
        $products = $data;
        return paginationResponse('success', 200, $products, request('showPerPage'));
    }

    public function vatPolicy()
    {
        return getVatPolicy();
    }

    public function deliveryCharges()
    {
        $cartResponse = agentCartItem();
        $carts = $cartResponse->getData(true); // This converts the JsonResponse into an associative array
        $totalWeight = collect($carts['items'] ?? [])->sum('item_total_weight');
        $delivery_charges = DeliveryCharge::where('status', 1)->where('max_weight', '>=', $totalWeight)->select('id', 'code', 'delivery_type', 'delivery_charge', 'vat_rate', 'max_weight', 'parcel_shop_status', 'description', 'status')->get();

        return response()->json([
            'status' => 'success',
            'cartItemsWeight' => $totalWeight,
            'data' => $delivery_charges
        ]);
    }


    public function billingAddress()
    {
        $data = Agent::with([
            'business' => function ($query) {
                $query->with([
                    'user_information'
                ]);
            }
        ])->find(authAgentInfo()['agent_id']);
        $address = [];
        $address['company_name'] = $data->business->user_information->company_name ?? "";
        $address['phone'] = $data->business->user_information->phone ?? "";
        $address['mobile'] = $data->business->mobile ?? "";
        $address['street'] = $data->business->user_information->street ?? "";
        $address['city'] = $data->business->user_information->city ?? "";
        $address['zip_code'] = $data->business->user_information->zip_code ?? "";
        $address['country'] = "SE" ?? "";

        return response()->json([
            'status' => 'success',
            'data' => $address
        ]);
    }

    public function submitOrder($request)
    {
        $agent = auth()->guard('agent')->user();
        $business_street =  $agent->business->user_information->street ?? '';
        $business_zip_code =  $agent->business->user_information->zip_code ?? '';
        $business_city =  $agent->business->user_information->city ?? '';
        $business_address = $business_street.', '.$business_zip_code. ' '.$business_city;
        //$address = Address::findOrFail($request->address);
        $cart_items = $request->cart['items'];

        $order = new Order();
        $order->agent_id = authAgentInfo()['agent_id'];
        $order->business_id = authAgentInfo()['business_id'];
        $order->tellecto_order_no = Order::getTellectoOrderNumber();
        $order->order_date = today();
        // Customer(Agent) information
        $order->customer_name = Agent::getAgentFullName(authAgentInfo()['agent_id']);
        $order->customer_mobile = $agent->phone ?? null;
        $order->customer_phone = $agent->phone ?? null;
        $order->customer_email = $agent->email ?? null;
        $order->from_country = "SE";
        $order->from_city = $agent->city ?? null;
        $order->from_zip = $agent->zip_code ?? null;
        $order->from_address = $business_address ?? null;
        // Delivery Address
        $order->delivery_name = $request->address['name'] ?? null;
        $order->delivery_phone = $request->address['phone'] ?? null;
        $order->delivery_address = $request->address['address'] ?? null;
        $order->delivery_zip = $request->address['zip_code'] ?? null;
        $order->requsition = $request->address['requsition'] ?? null;
        $order->delivery_city = $request->address['city'] ?? null;
        $order->delivery_street = $request->address['street'] ?? null;
        $order->delivery_mobile = $request->address['mobile'] ?? $request->address['phone'] ?? null;
        $order->delivery_country = "SE";
        // Delivery information
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
        $total_excluding_vat_admin = Order::getTotalItemPriceAdmin($cart_items); // DCS Purchase price sumetion
        $total_vat_admin = Order::getTotalVatAdmin($cart_items);
        $total_including_vat_admin = $total_excluding_vat_admin + $total_vat_admin;
        $total_order_amount_admin = $total_including_vat_admin + $dcs_delivery_charge;

        $order->total_excluding_vat_admin = $total_excluding_vat_admin + $dcs_delivery_charge; // DCS purchase + Delivery charge
        $order->total_vat_admin = $total_vat_admin;
        $order->total_including_vat_admin = $total_including_vat_admin;
        $order->total_order_amount_admin = $total_order_amount_admin;

        // Business Calculation
        $total_excluding_vat_business = Order::getTotalItemPriceBusiness($cart_items);
        $total_vat_business = Order::getTotalVatBusiness($cart_items);
        $total_including_vat_business = $total_excluding_vat_business + $total_vat_business;
        $total_order_amount_business = $total_including_vat_business + $delivery_charge_with_vat;

        $order->total_excluding_vat_business = $total_excluding_vat_business + $delivery_charge;
        $order->total_vat_business = $total_vat_business + $delivery_charge_vat;
        $order->total_including_vat_business = $total_including_vat_business + $total_vat_business;
        $order->total_order_amount_business = $total_order_amount_business;

        // Agent Calculation
        $total_excluding_vat_agent = Order::getTotalItemPriceAgent($cart_items);
        $total_vat_agent = Order::getTotalVatAgent($cart_items);
        $total_including_vat_agent = $total_excluding_vat_agent + $total_vat_agent;
        $total_order_amount_agent = $total_including_vat_agent + $delivery_charge_with_vat;

        $order->total_excluding_vat_agent = $total_excluding_vat_agent + $delivery_charge;
        $order->total_vat_agent = $total_vat_agent + $delivery_charge_vat;
        $order->total_including_vat_agent = $total_including_vat_agent;
        $order->total_order_amount_agent = $total_order_amount_agent;
        // Sales total for tellecto and business
        $order->total_sales_amount_admin = Order::getTotalSalesAmountAdmin($cart_items);
        $order->total_sales_amount_business = Order::getTotalSalesAmountBusiness($cart_items);

        $order->payment_method = $request->payment;
        $order->comment = $request->cart['comment'] ?? null;
        $order->order_status = 0;

        $order->created_by = authAgentInfo()['agent_id'];
        $order->updated_by = authAgentInfo()['agent_id'];

        // save order items
        if ($order->save()) {
            // Parcel shop information
            if($request->delivery_id == 3 && isset($request->parcel_shop) && !empty($request['parcel_shop']['shop_name'])) {
                $parcel_shop = new OrderParcelShop();
                $parcel_shop->order_id = $order->id;
                $parcel_shop->country = "SE";
                $parcel_shop->country_code = "SE";
                $parcel_shop->service_point_id = $request['parcel_shop']['service_point_id'] ?? null;
                $parcel_shop->shop_name = $request['parcel_shop']['shop_name'];
                $parcel_shop->city = $request['parcel_shop']['city'] ?? null;
                $parcel_shop->street_name = $request['parcel_shop']['street_name'] ?? null;
                $parcel_shop->street_number = $request['parcel_shop']['street_number'] ?? null;
                $parcel_shop->postal_code = $request['parcel_shop']['postal_code'] ?? null;
                $parcel_shop->save();
            }

            foreach ($cart_items as $item) {
                $product_id = $item['id'];

                $product_info = Product::findOrFail($product_id);
                $business_product = BusinessProductPrice::where('business_id', $order->business_id)->where('product_id', $product_id)->first();

                $order_item = new OrderItem();
                $order_item->order_id = $order->id ?? 1;
                $order_item->product_id = $product_id;

                $order_item->dcs_last_price = $product_info->price;
                $order_item->tellecto_last_price = $product_info->sale_price;
                $order_item->business_last_price = $business_product->sale_price;

                $item_quantity = $item['quantity'];
                $order_item->quantity = $item_quantity;
                $order_item->unit = $item['unit'] ?? 'Pcs';
                $order_item->size_id = $item['size_id'] ?? null;
                $order_item->color_id = $item['color_id'] ?? null;

                $item_total_agent = OrderItem::itemPriceAgent($product_id, $item_quantity);
                $item_total_business = OrderItem::itemPriceBusiness($product_id, $item_quantity);
                $item_total_admin = OrderItem::itemPriceAdmin($product_id, $item_quantity);

                $total_vat_agent = OrderItem::getItemVatAgent($product_id, $item_quantity)['total_vat'];
                $total_vat_business = OrderItem::getItemVatBusiness($product_id, $item_quantity)['total_vat'];
                $total_vat_admin = OrderItem::getItemVatAdmin($product_id, $item_quantity)['total_vat'];

                $order_item->item_total_agent = $item_total_agent;
                $order_item->item_total_business = $item_total_business;
                $order_item->item_total_admin = $item_total_admin;

                $order_item->vat_type_agent = OrderItem::getItemVatTypeAgent($product_id);
                $order_item->vat_type_business = OrderItem::getItemVatTypeBusiness($product_id);
                $order_item->vat_type_admin = OrderItem::getItemVatTypeAdmin($product_id);

                $order_item->total_vat_agent = $total_vat_agent;
                $order_item->total_vat_business = $total_vat_business;
                $order_item->total_vat_admin = $total_vat_admin;

                $order_item->total_price_agent = $item_total_agent + $total_vat_agent;
                $order_item->total_price_business = $item_total_business + $total_vat_business;
                $order_item->total_price_admin = $item_total_admin + $total_vat_admin;

                $order_item->total_sales_price_admin = OrderItem::itemSalesAmountAdmin($product_id, $item_quantity);
                $order_item->total_sales_price_business = OrderItem::itemSalesAmountBusiness($product_id, $item_quantity);

                $order_item->save();
                // update inventory
                $current_inventory = $product_info->inventory - $item_quantity;
                $product_info->inventory = $current_inventory;
                $product_info->save();
                // Update product sock status
                if ($current_inventory == 0) {
                    Product::find($product_id)->update(['stock_status' => 'NO']);
                }
            }
            // Save order log
            OrderLog::saveOrderLog($order->id, authAgentInfo()['agent_id'], 0);
            // Remove current cart for agent
            Cache::forget(cartKey());
            // Send confirmation mail to agent
            $mail_data = [
                'activity_type' => 'order_placed_to_agent',
                'to_email' => $order->customer_email,
                'to_name' => $order->customer_name,
                'subject' => 'Order Placed.',
                'mail_body' => "Your order " . $order->tellecto_order_no . " has been placed successfully."
            ];
            //sendMail($mail_data);

            // Send confirmation mail to business
            $mail_data = [
                'activity_type' => 'order_placed_by_agent_to_business',
                'view_file' => 'mail.order.layouts',
                'to_email' => $order->business->email,
                'to_name' => $order->business->name,
                'subject' => 'Order #'.$order->tellecto_order_no.' - Confirmation by Your Agent',
                'order' => $order
            ];
            sendMail($mail_data);

            return response()->json([
                'status' => 'success',
                'message' => 'You order has been placed successfully',
                'data' => $order
            ]);
        } else {
            return failedResponse();
        }
    }

    public function orderList()
    {
        $data = Order::query();
        $data->where("agent_id", auth()->guard('agent')->user()->id);
        $data->when(request()->get('tellecto_order_no'), function ($query) {
            $query->where('tellecto_order_no', request()->get('tellecto_order_no'));
        });
        $data->with([
            'order_items' => function ($item) {
                $item->agentSelectedFields();
                $item->with([
                    'product' => function ($product) {
                        $product->select('id', 'product_number', 'product_name');
                    }
                ]);
            }
        ]);
        $data->latest();
        $data->agentSelectedFields();
        return paginationResponse('success', 200, $data, request('showPerPage'));
    }

    protected function orderInfo($order_id)
    {
        $data = Order::with([
            'business' => function ($query) {
                $query->select('id', 'name', 'email', 'mobile');
                $query->with([
                    'user_information' => function ($info) {
                        $info->businessSelectedFields();
                    }
                ]);
            },
            'order_items' => function ($item) {
                $item->agentSelectedFields();
                $item->with([
                    'product' => function ($product) {
                        $product->select('id', 'product_number', 'product_name', 'model_name');
                    }
                ]);
            }
        ])->where('id', $order_id)->where('agent_id', authAgentInfo()['agent_id'] ?? 1)->agentSelectedFields()->first();
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

    public function downloadInvoice($order_id)
    {
        $data = $this->orderInfo($order_id);
        //return $data;
        //return view('pdf.agent_invoice',compact('data'));
        $file = PDF::loadView('pdf.agent_invoice', compact('data'));
        //return $file->stream();

        // Temporary saved on a directory
//        $folderPath = "assets/files/invoice/";
//        $fileName = "agent_invoice_" . $data->tellecto_order_no . ".pdf";
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

    public function nearestServiceByAddress()
    {
        $city = request()->get('city');
        $postalCode = request()->get('postal_code');
        $streetName = request()->get('street_name');
        $streetNumber = request()->get('street_number');
        $response = Http::get('https://api2.postnord.com/rest/businesslocation/v5/servicepoints/nearest/byaddress', [
            'apikey' => '6f828e156afcb91c638d8974205e2df1',
            'returnType' => 'json',
            'countryCode' => 'SE',
            'agreementCountry' => 'SE',
            'city' => $city,
            'postalCode' => $postalCode,
            'streetName' => $streetName,
            'streetNumber' => $streetNumber,
            'numberOfServicePoints' => '20',
            'srId' => 'EPSG:4326',
            'context' => 'optionalservicepoint',
            'responseFilter' => 'public',
            'typeId' => '24,25,54',
        ]);

        // Check if the request was successful
        if ($response->successful()) {
            // Decode and return the JSON response
            $data = $response->json();
            // Extract only the 'country' and 'deliveryAddress' fields from each service point
            $filteredData = [];

            if (isset($data['servicePointInformationResponse']['servicePoints'])) {

                // Extract relevant data from each service point
                foreach ($data['servicePointInformationResponse']['servicePoints'] as $servicePoint) {
                    $filteredData[] = [
                        'country' => $data['servicePointInformationResponse']['customerSupports'][0]['country'] ?? null,
                        'country_code' => $servicePoint['deliveryAddress']['countryCode'] ?? null,
                        'service_point_id' => $servicePoint['servicePointId'] ?? null,  // Same here
                        'shop_name' => $servicePoint['name'] ?? null,
                        'city' => $servicePoint['deliveryAddress']['city'] ?? null,
                        'street_name' => $servicePoint['deliveryAddress']['streetName'] ?? null,
                        'street_number' => $servicePoint['deliveryAddress']['streetNumber'] ?? null,
                        'postal_code' => $servicePoint['deliveryAddress']['postalCode'] ?? null,
                    ];
                }
            }


            return response()->json([
                'status' => 'success',
                'result_count' => count($filteredData),
                'data' => $filteredData
            ]);
        } else {
            // Return an error response if the request fails
            return response()->json([
                'error' => 'Failed to fetch service points'
            ], $response->status());
        }
    }
}


