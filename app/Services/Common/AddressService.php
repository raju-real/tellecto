<?php

namespace App\Services\Common;

use App\Models\Address;
use Illuminate\Http\JsonResponse;

/**
 * Class AddressService.
 */
class AddressService
{
    public function fetchAllAddress()
    {
        $data = Address::select('id', 'agent_id', 'address_type', 'street', 'city', 'address', 'zip_code', 'status')->get();
        return apiResponse('success', 200, $data);
    }
    public function storeAddress($data): JsonResponse
    {
        $address = new Address();
        $address->agent_id = authAgentInfo()['agent_id'];
        $address->address_type = $data->address_type;
        $address->street = $data->street;
        $address->city = $data->city;
        $address->address = $data->address;
        $address->zip_code = $data->zip_code;
        $address->status = $data->status;
        $address->save();

        if ($address) {
            return successResponse('success', 200, $address);
        } else {
            return failedResponse();
        }
    }

    public function updateAddress($data, $id): JsonResponse
    {
        $address = Address::findOrFail($id);
        $address->agent_id = authAgentInfo()['agent_id'];
        $address->address_type = $data->address_type;
        $address->street = $data->street;
        $address->city = $data->city;
        $address->address = $data->address;
        $address->zip_code = $data->zip_code;
        $address->status = $data->status;
        $address->save();

        if ($address) {
            return updateResponse('success', 200, $address);
        } else {
            return failedResponse();
        }
    }

    public function addressDetails($id)
    {
        $condition = [
            'id' => $id,
            'agent_id' => authAgentInfo()['agent_id']
        ];
        $data = Address::where($condition)->select('id', 'agent_id', 'address_type', 'street', 'city', 'address', 'zip_code', 'status')->firstOrFail();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
}
