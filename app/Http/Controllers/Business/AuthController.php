<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use App\Http\Requests\Business\businessRegisterRequest;
use App\Models\User;
use App\Models\UserInformation;
use App\Services\Admin\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function businessLogin(LoginRequest $request)
    {
        try {
            return $this->authService->businessLogin($request);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function businessRegister(businessRegisterRequest $data)
    {
        $user = new User();
        $user->role_id = 3;
        $user->username = $data->username;
        $user->name = $data->name;
        $user->email = $data->email;
        $user->mobile = $data->mobile;
        $user->password = Hash::make(Str::random(5));
        $user->is_active = false;
        $user->user_status = "pending";

        if ($data->file('image')) {
            $user->image = uploadImage($data->file('image'), 'admin');
        }
        if ($user->save()) {
            $row = new UserInformation();
            $row->user_id = $user->id;
            $row->company_name = $data->company_name;
            $row->org_no = $data->org_no;
            $row->vat_no = $data->vat_no;
//            $row->contact_person = $data->contact_person;
//            $row->business_type = $data->business_type;
            $row->website_url = $data->website;
//            $row->phone = $data->phone;
            $row->company_email = $data->company_email;
            if ($data->file('logo')) {
                $row->logo = uploadImage($data->file('logo'), 'profile');
            }
            $row->street = $data->street;
            $row->city = $data->city;
            $row->zip_code = $data->zip_code;

            if ($row->save()) {
                // Send email to business
                $mail_data = [
                    'activity_type' => 'registration_request_placed_to_business',
                    'view_file' => 'mail.layouts.app',
                    'to_email' => $user->email,
                    'to_name' => $user->name,
                    'subject' => 'Registration request placed successfully to Tellecto.',
                    'user' => $user
                ];
                sendMail($mail_data);

                // Send email to admin
                $tellecto_mail_data = [
                    'activity_type' => 'new_business_registration_to_admin',
                    'view_file' => 'mail.layouts.app',
                    'to_email' => 'info@tellecto.se',
                    'to_name' => 'Tellecto Admin',
                    'subject' => 'New Business Registration  – '. $user->user_information->company_name,
                    'user' => $user
                ];
                sendMail($tellecto_mail_data);
                return response()->json([
                    'message' => 'User registered successfully! Please wait for admin approval',
                    'user' => $user
                ], 201);
            } else {
                return failedResponse();
            }
        } else {
            return failedResponse();
        }

    }

    public function updateStatus($id, Request $request)
    {
        $data = $request->validate([
            'user_status' => 'required',
            'rejected_for' => 'nullable|required_if:user_status,suspended'
        ]);

        $business = User::find($id);

        if (!$business) {
            return response()->json([
                'status' => 'error',
                'code' => 404,
                'message' => 'Business not found',
            ], 404);
        }

        // Update the user_status and send email notification if status has changed
        if ($business->user_status !== $data['user_status']) {
            $business->user_status = $data['user_status'];
            $business->save();

            // Retrieve password only if status is accepted
            if ($business->user_status === 'accept') {
                $business_password = Str::random(5);
                User::where('id',$business->id)->update(['password' => Hash::make($business_password)]);
                $mail_data = [
                    'activity_type' => 'business_approved_by_tellecto_to_business',
                    'view_file' => 'mail.layouts.app',
                    'to_email' => $business->email,
                    'to_name' => $business->name,
                    'subject' => 'Your Business Account Has Been Approved – Welcome to TELLECTO',
                    'user' => $business,
                    'password' => $business_password
                ];
                sendMail($mail_data);
            } else {
                UserInformation::where("user_id",$business->id)->update(['rejected_for' => $data['rejected_for']]);
                $mail_data = [
                    'activity_type' => 'business_rejected_by_tellecto_to_business',
                    'view_file' => 'mail.layouts.app',
                    'to_email' => $business->email,
                    'to_name' => $business->name,
                    'subject' => 'Application Rejected – Business Account Registration',
                    'user' => $business
                ];
                sendMail($mail_data);
            }
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Business status updated successfully',
            'business' => $business,
        ]);
    }


    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            "message" => "logged out"
        ]);

    }

}
