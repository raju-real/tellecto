<?php

namespace App\Services\Admin;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\UserInformation;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

/**
 * Class AdminService.
 */
class AdminService
{
    public function fetchAllAdmin(): JsonResponse
    {
        $data = User::query();
        $data->latest();
        $data->admin();
        $data->with(['role_info', 'user_information' => function ($query) {
            $query->adminSelectedFields();
        }]);
        $data->when(request()->get('name'),function ($query) {
            $name = request()->get('name');
           $query->where('name',"LIKE","%{$name}%");
        });
        $data->when(request()->get('email'),function ($query) {
            $email = request()->get('email');
           $query->where('email',"LIKE","%{$email}%");
        });
        $data->when(request()->get('mobile'),function ($query) {
            $mobile = request()->get('mobile');
           $query->where('mobile',"LIKE","%{$mobile}%");
        });
        if(request()->has('is_active')) {
            $data->where('is_active', request()->get('is_active'));
        }
        if(request()->has('role_id')) {
            $data->where('role_id', request()->get('role_id'));
        }
        $data->selectedFields();
        $showPerPage = request()->get('showPerPage');
        if ($showPerPage == "All") {
            return apiResponse('success', 200, $data->get());
        } else {
            return paginationResponse('success', 200, $data, request('showPerPage'));
        }
    }

    public function storeAdmin($data): JsonResponse
    {
        $user = new User();
        $user->role_id = $data->role_id;
        $user->username = $data->username;
        $user->name = $data->name;
        $user->email = $data->email;
        $user->mobile = $data->mobile;
        $user->password = Hash::make($data->password);
        $user->is_active = $data->is_active ?? true;
        $user->user_status="accept";
        if ($data->file('image')) {
            $user->image = uploadImage($data->file('image'), 'admin');
        }
        if($user->save()) {
            $row = new UserInformation();
            $row->user_id = $user->id;
            $row->employee_id = $data->employee_id;
            $row->joining_date = $data->joining_date;
            $row->save();
            return successMessageResponse('success',200);
        } else {
            return failedResponse();
        }
    }

    public function adminById($id): JsonResponse
    {
        $admin = User::select('id','role_id','name','email','mobile','username','image','is_active')->admin()->where('id',$id)->first();
        return apiResponse('success',200, new UserResource($admin));
    }

    public function updateAdmin($data, $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $user->role_id = $data->role_id;
        $user->username = $data->username;
        $user->name = $data->name;
        $user->email = $data->email;
        $user->mobile = $data->mobile;
        $user->is_active = $data->is_active ?? true;
        if(!empty($data->password)) {
            $user->password = Hash::make($data->password);
        }
        if ($data->file('image')) {
            $user->image = uploadImage($data->file('image'), 'admin');
        }
        if($user->save()) {
            $row = UserInformation::where('user_id',$user->id)->firstOrFail();
            $row->user_id = $user->id;
            $row->employee_id = $data->employee_id;
            $row->joining_date = $data->joining_date;
            $row->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Information has been updated successfully!'
            ]);
        } else {
            return failedResponse();
        }
    }

    public function changeAdminStatus(int $id): JsonResponse
    {
        $user = User::admin()->findOrFail($id);
        $user->is_active = $user->is_active == 1 ? 0 : 1;
        $user->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Admin status has been changed successfully!'
        ]);
    }

}
