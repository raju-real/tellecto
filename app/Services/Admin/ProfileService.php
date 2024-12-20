<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Models\UserInformation;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

/**
 * Class ProfileService.
 */
class ProfileService
{
    public function adminProfile():JsonResponse

    {
        $admin = User::with('user_information')->findOrFail(auth()->user()->id);
        return apiResponse('success',200,$admin);
    }
    public function updateAdminProfile($data): JsonResponse
    {

        $user = User::findOrFail(auth()->user()->id);
        $user->username = $data->username;
        $user->name = $data->name;
        $user->email = $data->email;
        $user->mobile = $data->mobile;
        $user->is_active = $data->is_active ?? true;
        if (!empty($data->password)) {
            $user->password = Hash::make($data->password);
        }
        if ($data->file('image')) {
            $user->image = uploadImage($data->file('image'), 'admin');
        }
        if ($user->save()) {
            $row = UserInformation::where('user_id', $user->id)->firstOrFail();
            $row->user_id = $user->id;
            $row->employee_id = $data->employee_id;
            $row->company_name = $data->company_name;
            $row->joining_date = $data->joining_date;
            $row->org_no = $data->org_no;
            $row->vat_no = $data->vat_no;
            $row->contact_person = $data->contact_person;
            $row->business_type = $data->business_type;
            $row->website_url = $data->website_url;
            $row->phone = $data->phone;
            $row->company_email = $data->company_email;
            $row->logo = $data->logo;
            $row->street = $data->street;
            $row->city = $data->city;
            $row->zip_code = $data->zip_code;
            $row->save();
            return updateMessageResponse('success', 200);
        } else {
            return failedResponse();
        }
    }

}
