<?php

namespace App\Services\Admin;

use App\Models\Agent;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

;

/**
 * Class AgentService.
 */
class AgentService
{
    public function fetchAllAgent(): JsonResponse
    {

        $data = Agent::query()
            ->select('id', 'first_name', 'last_name', 'agent_code', 'email', 'phone', 'status')
            ->orderBy('id', 'DESC');
        $data->when(request()->get('search'), function ($query) {
            $search = request()->get('search');
            $query->where('first_name', "LIKE", "%{$search}%");
            $query->orWhere('first_name', "LIKE", "%{$search}%");
        });
        $data->when(request()->get('business_id'), function ($query) {
            $query->where('business_id', request()->get('business_id'));
        });
        $data->when(request()->get('agent_code'), function ($query) {
            $query->where('agent_code', request()->get('agent_code'));
        });
        if (request()->has('status')) {
            $data->where('status', request()->get('status'));
        }

        $data->when(request()->get('email'), function ($query) {
            $email = request()->get('email');
            $query->where('email', "LIKE", "%{$email}%");
        });
        $data->when(request()->get('phone'), function ($query) {
            $phone = request()->get('phone');
            $query->where('phone', "LIKE", "%{$phone}%");
        });
        $data->when(request()->get('search'), function ($query) {
            $search = request()->get('search');
            $query->where(function ($query) use ($search) {
                $query->where('first_name', "LIKE", "%{$search}%")
                    ->orWhere('last_name', $search)
                    ->orWhere('email', "LIKE", "%{$search}%")
                    ->orWhere('phone', $search);
            });
        });
        $data->select('id', 'business_id', 'agent_code', 'personal_id', 'manager_name', 'first_name', 'last_name', 'phone', 'email', 'image', 'street', 'city', 'zip_code', 'password', 'status');
        $agents = $data;
        return paginationResponse('success', 200, $agents, 50);
    }

    public function allAgent()
    {
        $data = Agent::query();
        $data->when(request()->get('business_id'), function ($query) {
            $query->where('business_id', request()->get('business_id'));
        });
        $data->select('id', 'business_id', 'first_name', 'last_name');
        $agents = $data->get();
        return response()->json([
            'status' => 'success',
            'data' => $agents
        ]);
    }

    public function storeAgent($data): JsonResponse
    {
        $row = new Agent();
        $row->business_id = $data->business_id;
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
        $agent = Agent::find($id);
        return apiResponse('success', 200, $agent);
    }

    public function updateAgent($data, $id): JsonResponse
    {
        $row = Agent::findOrFail($id);
        $row->business_id = $data->business_id;
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
        $row->status = $data->status;
        if ($data->file('image')) {
            $row->image = uploadImage($data->file('image'), 'profile');
        }

        if ($row->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Information has been updated successfully!'
            ]);
        } else {
            return failedResponse();
        }
    }

    public function deleteAgent(int $id): JsonResponse
    {
        $agent = Agent::findOrFail($id);
        if ($agent->delete()) {
            return deleteResponse('success', 200);
        } else {
            return failedResponse();
        }
    }

    public function changeAgentPassword($requestData, $id): JsonResponse
    {
        $user = Agent::findOrFail($id);
        $user->password = Hash::make($requestData->confirm_password);
        $user->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Agent password has been changed successfully!'
        ]);
    }
}
