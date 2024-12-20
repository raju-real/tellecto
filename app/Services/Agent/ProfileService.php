<?php

namespace App\Services\Agent;

use App\Models\Agent;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Class ProfileService.
 */
class ProfileService
{
    public function agentProfile()
    {
        $agent = Agent::findOrFail(auth()->guard('agent')->user()->id);
        return $agent;
    }
    public function updateAgentProfile($data): JsonResponse
    {
        $row = Agent::findOrFail(auth()->guard('agent')->user()->id);
        $row->manager_name = $data->manager_name;
        $row->first_name = $data->first_name;
        $row->last_name = $data->last_name;
        $row->phone = $data->phone;
        $row->email = $data->email;
        $row->street = $data->street;
        $row->city = $data->city;
        $row->zip_code = $data->zip_code;
        $row->status = $data->status;
        if ($data->file('image')) {
            $row->image = uploadImage($data->file('image'), 'profile');
        }

        if($row->save()) {
            return updateResponse('success',200,$row);
        } else {
            return failedResponse();
        }
    }

    public function changePassword($data)
    {
        Auth::user()->update(['password' => Hash::make($data->confirm_password)]);
        return response()->json([
            'status' => 'success',
            'message' => 'Information has been updated successfully!'
        ]);
    }
}
