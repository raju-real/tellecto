<?php

namespace App\Services\Business;

//use App\Models\Business;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

/**
 * Class ProfileService.
 */
class ProfileService
{

    public function businessProfile()
    {
        $business = User::with('user_information')->findOrFail(auth()->user()->id);
        return apiResponse('success',200,$business);
    }

    public function updateBusinessProfile($data): JsonResponse
    {

        $row = User::findOrFail(auth()->user()->id);
//        $row->company_name = $data->company_name;
//        $row->org_no = $data->org_no;
//        $row->vat_no = $data->vat_no;
//        $row->contact_person = $data->contact_person;
//        $row->business_type = $data->business_type;
//        $row->website_url = $data->website_url;
//        $row->first_name = $data->first_name;
//        $row->last_name = $data->last_name;
        $row->name = $data->name;
        $row->mobile = $data->mobile;
        $row->email = $data->email;
//        $row->street = $data->street;
//        $row->city = $data->city;
//        $row->zip_code = $data->zip_code;
        $row->password=Hash::make($data->password);

//
//        if ($data->file('logo')) {
//            $row->logo = uploadImage($data->file('logo'), 'profile');
//        }

        if ($row->save()) {
            return updateMessageResponse('success', 200);
        } else {
            return failedResponse();
        }
    }
}
