<?php

namespace App\Services\Business;

use App\Models\Agent;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Class AgentService.
 */
class AgentService
{
    public function fetchAllAgent(): JsonResponse
    {

        $data = Agent::query();
        $data->latest();
        $data->where('business_id', authUserId());
        $data->when(request()->get('name'), function ($query) {
            $name = request()->get('name');
            $query->where('name', "LIKE", "%{$name}%");
        });
        $data->select('id', 'business_id', 'agent_code', 'personal_id', 'manager_name', 'first_name', 'last_name', 'phone', 'email', 'image', 'street', 'city', 'zip_code', 'password', 'status');
        $agent = $data;
        return paginationResponse('success', 200, $agent, request('showPerPage'));
    }

    public function agentList()
    {
        $agents = Agent::where('business_id', authUserId())->select('id', 'first_name', 'last_name')->get();
        return response()->json([
            'status' => 'success',
            'data' => $agents
        ]);
    }

    public function storeAgent($data): JsonResponse
    {
        $row = new Agent();
        $row->business_id = Auth::id();
        $row->agent_code = $data->agent_code;
        $row->personal_id = $data->personal_id;
        $row->manager_name = $data->manager_name;
        $row->first_name = $data->first_name;
        $row->last_name = $data->last_name;
        $row->phone = $data->phone;
        $row->email = $data->email;
        $row->street = $data->street;
        $row->city = $data->city;
        $row->zip_code = $data->zip_code;
        $row->password = Hash::make($data->password);
        $row->status = $data->status;
        if ($data->file('image')) {
            $row->image = uploadImage($data->file('image'), 'profile');
        }
        if ($row->save()) {
            $agent_mail_data = [
                'activity_type' => 'welcome_message_on_registration_to_agent',
                'view_file' => 'mail.layouts.app',
                'to_email' => $row->email,
                'to_name' => $row->first_name . ' ' . $row->last_name,
                'subject' => 'Welcome to TELLECTO – Your Agent Account Has Been Created',
                'agent' => $row,
                'password' => $data->password
            ];
            sendMail($agent_mail_data);
            return successResponse('success', 200, $row);
        } else {
            return failedResponse();
        }
    }

    public function agentById($id): JsonResponse
    {
        $agent = Agent::where('id', $id)->where('business_id', authUserId())->firstOrFail();
        return apiResponse('success', 200, $agent);
    }

    public function updateAgent($data, $id): JsonResponse
    {
        $row = Agent::findOrFail($id);
        //$row->business_id = $data->business_id;
        $row->agent_code = $data->agent_code;
        $row->personal_id = $data->personal_id;
        $row->manager_name = $data->manager_name;
        $row->first_name = $data->first_name;
        $row->last_name = $data->last_name;
        $row->phone = $data->phone;
        $row->email = $data->email;
        $row->street = $data->street;
        $row->city = $data->city;
        $row->zip_code = $data->zip_code;
        if (isset($request->password)) {
            $row->password = Hash::make($data->password);
        }
        $row->status = $data->status;
        if ($data->file('image')) {
            $row->image = uploadImage($data->file('image'), 'profile');
        }
        if ($row->save()) {
            return updateResponse('success', 200, $row);
        } else {
            return failedResponse();
        }
    }

//    public function deleteAgent(int $id): JsonResponse
//    {
//        $agent = Agent::findOrFail($id);
//
//        if($agent->delete()) {
//            return deleteResponse('success',200);
//        } else {
//            return failedResponse();
//        }
//    }
}
