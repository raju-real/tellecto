<?php

namespace App\Services\Admin;

use App\Models\DeliveryCharge;

/**
 * Class ShippingMethodService.
 */
class ShippingMethodService
{
    public function fetchAllShippingMethod()
    {
        $data = DeliveryCharge::query();
        $data->when(request()->get('code'), function ($query) {
            $code = request()->get('code');
            $query->where('code', "LIKE", "%{$code}%");
        });
        $data->when(request()->get('delivery_type'), function ($query) {
            $delivery_type = request()->get('delivery_type');
            $query->where('delivery_type', "LIKE", "%{$delivery_type}%");
        });
        $data->when(request()->get('delivery_dcs'), function ($query) {
            $delivery_dcs = request()->get('delivery_dcs');
            $query->where('delivery_dcs', "LIKE", "%{$delivery_dcs}%");
        });

        $data->select('id','code','dcs_charge','delivery_type','delivery_dcs','delivery_charge','vat_rate','max_weight', 'parcel_shop_status', 'description', 'status');
        return paginationResponse('success', 200, $data, request('showPerPage'));
    }

    public function allShippingMethod()
    {
        $data = DeliveryCharge::select('id','code','dcs_charge','delivery_type','delivery_dcs','delivery_charge','vat_rate','max_weight', 'parcel_shop_status', 'description', 'status')->get();
        return apiResponse('success', 200, $data);
    }

    public function storeShippingMethod($requestData)
    {
        $data = new DeliveryCharge();
        $data->code = $requestData->code;
        $data->dcs_charge = $requestData->dcs_charge;
        $data->delivery_type = $requestData->delivery_type;
        $data->delivery_dcs = $requestData->delivery_dcs;
        $data->delivery_charge = $requestData->delivery_charge;
        $data->vat_rate = $requestData->vat_rate;
        $data->max_weight = $requestData->max_weight;
        $data->parcel_shop_status = $requestData->parcel_shop_status ?? 0;
        $data->description = $requestData->description;
        $data->status = $requestData->status;
        $data->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Information has been saved successfully!'
        ]);
    }

    public function shippingMethodDetails($id)
    {
        return response()->json([
            'status' => 'success',
            'data' => DeliveryCharge::select('id','code','dcs_charge','delivery_type','delivery_dcs','delivery_charge','vat_rate','max_weight', 'parcel_shop_status', 'description', 'status')->find($id)
        ]);
    }

    public function updateShippingMethod($id,$requestData)
    {
        $data = DeliveryCharge::findOrFail($id);
        $data->code = $requestData->code;
        $data->dcs_charge = $requestData->dcs_charge;
        $data->delivery_type = $requestData->delivery_type;
        $data->delivery_dcs = $requestData->delivery_dcs;
        $data->delivery_charge = $requestData->delivery_charge;
        $data->vat_rate = $requestData->vat_rate;
        $data->max_weight = $requestData->max_weight;
        $data->parcel_shop_status = $requestData->parcel_shop_status ?? 0;
        $data->description = $requestData->description;
        $data->status = $requestData->status;
        $data->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Information has been updated successfully!'
        ]);
    }

    public function deleteShippingMethod($id)
    {
        DeliveryCharge::where('id',$id)->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Information has been deleted successfully!'
        ]);
    }
}
