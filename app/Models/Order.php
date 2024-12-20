<?php

namespace App\Models;

use App\Helper\DCSHelper;
use App\Traits\DateFilter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use niklasravnsborg\LaravelPdf\Facades\Pdf;

class Order extends Model
{
    use HasFactory, DateFilter;

    protected $guarded = [];
    protected $appends = ['order_status_title', 'agent_billing_address', 'order_status_color', 'order_date_time', 'parcel_shop', 'total_shipping_vat'];

    public function getTrackingNumberAttribute($value)
    {
        return $value
            ? collect(json_decode($value, true))
            : collect();
    }


    public function getTotalShippingVatAttribute()
    {
        return $this->delivery_charge_with_vat - $this->delivery_charge;
    }


    public function getParcelShopAttribute()
    {
        if (OrderParcelShop::where('order_id', $this->id)->exists()) {
            $parcel_shop = [];
            $data = OrderParcelShop::where('order_id', $this->id)->first();
            $parcel_shop['service_point_id'] = $data->service_point_id;
            $parcel_shop['shop_name'] = $data->shop_name;
            $parcel_shop['city'] = $data->city;
            $parcel_shop['street_name'] = $data->street_name;
            $parcel_shop['street_number'] = $data->street_number;
            $parcel_shop['postal_code'] = $data->postal_code;
            $parcel_shop['address_line'] = $data->shop_name . ', ' . $data->street_name . ' ' . $data->street_number . ', ' . $data->postal_code . ' ' . $data->city;
            return $parcel_shop;
        } else {
            return null;
        }
    }

    public function getOrderDateTimeAttribute()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    public function getOrderStatusTitleAttribute()
    {
        $order_status = $this->order_status;
        return Order::orderStatusTitle($order_status);
    }

    public function getAgentBillingAddressAttribute()
    {
        $business_id = $this->business_id;
        $user_information = UserInformation::where('user_id', $business_id)->first();
        return $user_information;
    }

    public static function getOrderLogAttribute($order_id)
    {
        $logs = OrderLog::with(['order', 'user', 'agent'])->where('order_id', $order_id)->get();
        $order_logs = [];
        foreach ($logs as $log) {
            if ($log->order_status == 0) {
                $log_title = "The order " . $log->order->order_no . ' has been ' . $log->activity_name . '(' . Agent::getAgentFullName($log->user_id) . ')' . ' on ' . date('Y-m-d h:i');
            } else {
                $log_title = "The order " . $log->order->order_no . ' has been ' . $log->activity_name . '(' . $log->user->name . ')' . ' on ' . date('Y-m-d h:i');
            }
            array_push($order_logs, $log_title);
        }

        return response()->json([
            'status' => 'success',
            'data' => $order_logs
        ]);
    }

    public static function getTotalItemPriceAdmin($cart_items)
    {
        $total_item_price = 0;
        foreach ($cart_items as $item) {
            $total_price = OrderItem::itemPriceAdmin($item['id'], $item['quantity']);
            $total_item_price += $total_price;
        }
        return $total_item_price;

    }

    public static function getTotalVatAdmin($cart_items)
    {
        $total_vat_amount = 0.00;
        foreach ($cart_items as $item) {
            $vat_info = OrderItem::getItemVatAdmin($item['id'], $item['quantity']);
            $vat_amount = $vat_info['total_vat'];
            $total_vat_amount += $vat_amount;
        }
        return $total_vat_amount;
    }

    public static function getTotalItemPriceBusiness($cart_items)
    {
        $total_item_price = 0;
        foreach ($cart_items as $item) {
            $total_price = OrderItem::itemPriceBusiness($item['id'], $item['quantity']);
            $total_item_price += $total_price;
        }
        return $total_item_price;

    }

    public static function getTotalVatBusiness($cart_items)
    {
        $total_vat_amount = 0.00;
        foreach ($cart_items as $item) {
            $vat_info = OrderItem::getItemVatBusiness($item['id'], $item['quantity']);
            $vat_amount = $vat_info['total_vat'];
            $total_vat_amount += $vat_amount;
        }
        return $total_vat_amount;
    }

    public static function getTotalItemPriceAgent($cart_items)
    {
        $total_item_price = 0;
        foreach ($cart_items as $item) {
            $total_price = OrderItem::itemPriceAgent($item['id'], $item['quantity']);
            $total_item_price += $total_price;
        }
        return $total_item_price;

    }

    public static function getTotalVatAgent($cart_items)
    {
        $total_vat_amount = 0.00;
        foreach ($cart_items as $item) {
            $vat_info = OrderItem::getItemVatAgent($item['id'], $item['quantity']);
            $vat_amount = $vat_info['total_vat'];
            $total_vat_amount += $vat_amount;
        }
        return $total_vat_amount;
    }

    public static function getTotalPrice($order_id, $vendor)
    {
        $order = Order::findOrFail($order_id);
        $order_products = OrderItem::where('order_id', $order_id)->get();
        $delivery_charge = $order->delivery_charge ?? 0.00;
        $freight_cost = $order->freight_cost ?? 0.00;
        $total_vat = $order->total_vat ?? 0.00;
        $order_price = [];
        if ($vendor === "admin") {
            return $order->total_order_amount;
        } elseif ($vendor === "business") {
            $order_amount = 0;
            foreach ($order_products as $product) {
                $item_total = $product->tellecto_last_price * $product->quantity;
                $order_amount += $item_total;
            }

            $total_including_vat = $order_amount + $delivery_charge + $freight_cost + $total_vat;
            $total_excluding_vat = $order_amount + $delivery_charge + $freight_cost;
            $order_price['total_including_vat'] = number_format($total_including_vat, 2);
            $order_price['total_excluding_vat'] = number_format($total_excluding_vat, 2);
            return $order_price;
        } elseif ($vendor === "agent") {
            return $order->total_order_amount;
        }
    }

    public static function getTotalSalesAmountAdmin($cart_items)
    {
        $total_item_price = 0;
        foreach ($cart_items as $item) {
            $total_price = OrderItem::itemSalesAmountAdmin($item['id'], $item['quantity']);
            $total_item_price += $total_price;
        }
        return $total_item_price;
    }

    public static function getTotalSalesAmountBusiness($cart_items)
    {
        $total_item_price = 0;
        foreach ($cart_items as $item) {
            $total_price = OrderItem::itemSalesAmountBusiness($item['id'], $item['quantity']);
            $total_item_price += $total_price;
        }
        return $total_item_price;
    }

    public static function orderStatusTitle($order_status)
    {
        if ($order_status == 0) {
            return "Pending";
        } elseif ($order_status == 1) {
            return "Confirmed By Business";
        } elseif ($order_status == 2) {
            return "Canceled by Business";
        } elseif ($order_status == 3) {
            return "Approved By Tellecto";
        } elseif ($order_status == 4) {
            return "Declined By Tellecto";
        } elseif ($order_status == 5) {
            return "Processing";
        } elseif ($order_status == 6) {
            return "Shipped";
        } elseif ($order_status == 7) {
            return "Delivered";
        } else {
            return "Unknown";
        }
    }

    public static function orderStatusList()
    {
        $order_status = [];
        $order_status [] = ['id' => 0, 'name' => "Pending"];
        $order_status [] = ['id' => 1, 'name' => "Confirmed By Business"];
        $order_status [] = ['id' => 2, 'name' => "Canceled by Business"];
        $order_status [] = ['id' => 3, 'name' => "Approved By Tellecto"];
        $order_status [] = ['id' => 4, 'name' => "Declined By Tellecto"];
//        $order_status [] =  ['id' => 5, 'name' => "Processing"];
//        $order_status [] =  ['id' => 6, 'name' => "Shipped"];
        $order_status [] = ['id' => 7, 'name' => "Delivered"];
        return $order_status;
    }

    public function getOrderStatusColorAttribute()
    {
        if ($this->order_status == 0) {
            return "#808080";
        } elseif ($this->order_status == 1) {
            return "#0000FF";
        } elseif ($this->order_status == 2) {
            return "#8B0000";
        } elseif ($this->order_status == 3) {
            return "#0000FF";
        } elseif ($this->order_status == 4) {
            return "#8B0000";
        } elseif ($this->order_status == 5) {
            return "#8B0000";
        } elseif ($this->order_status == 6) {
            return "#097969";
        } elseif ($this->order_status == 7) {
            return "#097969";
        } else {
            return "#808080";
        }
    }

    public function business()
    {
        return $this->belongsTo(User::class, 'business_id', 'id');
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id', 'id');
    }

    public function order_items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    public function scopePending($query)
    {
        return $query->where('order_status', 0);
    }

    public function scopeNotPending($query)
    {
        return $query->where('order_status', '!=', 0);
    }

    public function scopeInvoice($query)
    {
        return $query->whereNotIn('order_status', [0, 2]);
    }

    public function scopeAdminOrder($query)
    {
        return $query->whereNotIn('order_status', [0, 2]);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('order_status', 1);
    }

    public function scopeCanceled($query)
    {
        return $query->where('order_status', 2);
    }

    public function scopePendingOrConfirmed($query)
    {
        return $query->where(function ($query) {
            $query->pending()->orWhere(function ($subQuery) {
                $subQuery->confirmed();
            });
        });
    }

    public function scopePendingOrCanceled($query)
    {
        return $query->where(function ($query) {
            $query->pending()->orWhere(function ($subQuery) {
                $subQuery->canceled();
            });
        });
    }

    public function scopeApproved($query)
    {
        return $query->where('order_status', 3);
    }

    public function scopeDeclined($query)
    {
        return $query->where('order_status', 4);
    }

    public function scopeConfirmedOrDeclined($query)
    {
        return $query->where(function ($query) {
            $query->confirmed()->orWhere(function ($subQuery) {
                $subQuery->declined();
            });
        });
    }

    public function scopeProcessing($query)
    {
        return $query->where('order_status', 5);
    }

    public static function getTellectoOrderNumber()
    {
        $latestOrderNumber = Order::latest('id')->first();
        $newOrderNumber = str_pad(1, 4, "0", STR_PAD_LEFT);
        if ($latestOrderNumber) {
            $lastOrderNumber = $latestOrderNumber->tellecto_order_no;
            if ($lastOrderNumber != null) {
                $newSerialNumber = $lastOrderNumber + 1;
                $newOrderNumber = str_pad($newSerialNumber, 4, "0", STR_PAD_LEFT);;
            } else {
                $newOrderNumber = str_pad(1, 4, "0", STR_PAD_LEFT);
            }
        }
        if (Order::where('tellecto_order_no', $newOrderNumber)->exists()) {
            Order::getOrderNumber();
        }
        return $newOrderNumber;
    }

    public static function placedOrderToDCS($order_id)
    {
        $order = Order::with('business', 'agent', 'order_items', 'order_items.product')->findOrFail($order_id);
        $delivery_option = DeliveryCharge::find($order->delivery_id);
        if (isset($order)) {
            $product_info = [];
            $order_info["rekvisition"] = $order->requsition ?? $order->order_no;
            $order_info["ignoreRekvisitionCheck"] = true;
            $order_info["deliverycountry"] = "SE";
            $order_info["fromcountry"] = "SE";
            if ($order->parcel_shop) {
                $order_info['servicepointid'] = $order['parcel_shop']['service_point_id'];
                $order_info['deliveryaddress1'] = $order['parcel_shop']['address_line'];
                $order_info['deliveryaddress2'] = 'Pakkeshop: ' . $order['parcel_shop']['service_point_id'];
            } else {
                $order_info["deliveryaddress1"] = $order->delivery_address;
            }
            $order_info["deliverytype"] = $delivery_option->delivery_dcs;
            $order_info["deliveryname"] = $order->delivery_name;
            $order_info["deliverymobile"] = $order->delivery_mobile ?? $order->delivery_phone;
            $order_info["deliveryphone"] = $order->delivery_phone;
            $order_info["deliveryemail"] = "info@tellecto.se";
            $order_info["fromname"] = $order->business->user_information->company_name;
            $order_info["fromaddress1"] = $order->from_address;
            $order_info["fromzip"] = $order->from_zip;
            $order_info["fromcity"] = $order->from_city;
            $order_info["comment"] = $order->comment;
            $order_info["deliveryzip"] = $order->delivery_zip;
            $order_info["deliverycity"] = $order->delivery_city;
            $order_info["deliveryattention"] = $order->delivery_name;
            $order_info["promotioncode"] = 'promotioncode';
            $order_info["fromemail"] = "info@tellecto.se";
//                $order_info["glspakkeshopid"] = 527474;

            if (count($order->order_items)) {
                $items = $order->order_items->map(function ($item) {
                    return [
                        "vare_nr" => $item->product->product_number,
                        "quantity" => $item->quantity,
                        "price" => $item->dcs_last_price,
                    ];
                });
            } else {
                return failedResponse();
            }

            $product_info["orderlines"] = $items->toArray();
        }
        $dcs = new DCSHelper();
        $response = $dcs->placeOrder($order_info, $product_info);
        return $response;
    }

    public static function reserveorder($order_id)
    {
        $order = Order::with('business', 'agent', 'order_items', 'order_items.product')->findOrFail($order_id);
        if (isset($order)) {
            $product_info = [];
            $order_info["rekvisition"] = $order->tellecto_order_no;
            $order_info["ignoreRekvisitionCheck"] = true;
            $order_info["comment"] = $order->comment;
            if (count($order->order_items)) {
                $items = $order->order_items->map(function ($item) {
                    return [
                        "vare_nr" => $item->product->product_number,
                        "quantity" => $item->quantity,
                        "price" => $item->dcs_last_price,
                    ];
                });
            } else {
                return failedResponse();
            }

            $order_info["orderlines"] = $items->toArray();
            $dcs = new DCSHelper();
            $response = $dcs->getReserveStatus($order_info);
            return $response;
        } else {
            return failedResponse();
        }
    }

    public static function trackOrder($order_id)
    {
        $order = Order::with('business', 'agent', 'order_items', 'order_items.product')->findOrFail($order_id);
        if (isset($order)) {
            if (count($order->order_items)) {
                $items = $order->order_items->map(function ($item) use ($order) {
                    return [
                        "ordernumber" => $order->dcs_order_no
                    ];
                });
            } else {
                return failedResponse();
            }

            $order_info["ordernumbers"] = $items->toArray();
            $dcs = new DCSHelper();
            $response = $dcs->getTrackStatus($order_info);
            return $response;
        } else {
            return failedResponse();
        }
    }

    public function scopeAgentSelectedFields($query)
    {
        $excludedFields = ['deleted_at', 'created_by', 'updated_by', 'updated_at', 'invoice_no', 'total_excluding_vat_admin', 'total_vat_admin', 'total_including_vat_admin', 'total_order_amount_admin', 'total_excluding_vat_business', 'total_vat_business', 'total_including_vat_business', 'total_order_amount_business'];
        $allColumns = Schema::getColumnListing('orders');
        $selectedFields = array_diff($allColumns, $excludedFields);
        return $query->select($selectedFields);
    }

    public function scopeBusinessSelectedFields($query)
    {
        $excludedFields = ['deleted_at', 'created_by', 'updated_by', 'updated_at', 'invoice_no', 'total_excluding_vat_admin', 'total_vat_admin', 'total_including_vat_admin', 'total_order_amount_admin'];
        $allColumns = Schema::getColumnListing('orders');
        $selectedFields = array_diff($allColumns, $excludedFields);
        return $query->select($selectedFields);
    }

    public function scopeAdminSelectedFields($query)
    {
        $excludedFields = ['deleted_at'];
        $allColumns = Schema::getColumnListing('orders');
        $selectedFields = array_diff($allColumns, $excludedFields);
        return $query->select($selectedFields);
    }

    public static function saveBusinessInvoice($order_id)
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
        $folderPath = "assets/files/invoice/";
        $fileName = "business_invoice_" . $data->tellecto_order_no . ".pdf";
        File::isDirectory($folderPath) || File::makeDirectory($folderPath, 0777, true, true);
        $file->save($folderPath . $fileName);
    }
}
