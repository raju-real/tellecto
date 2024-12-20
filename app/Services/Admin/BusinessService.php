<?php

namespace App\Services\Admin;

use App\Jobs\SetBusinessProduct;
use App\Models\Business;
use App\Models\User;
use App\Models\UserInformation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Class BusinessService.
 */
class BusinessService
{
    public function fetchAllBusinessOld(): JsonResponse
    {

        $data = Business::query()
            ->select('id', 'company_name', 'contact_person', 'email', 'phone', 'logo', 'status')
            ->orderBy('id', 'DESC');
        $data->when(request()->get('company_name'), function ($query) {
            $company_name = request()->get('company_name');
            $query->where('company_name', "LIKE", "%{$company_name}%");
        });
        $data->when(request()->get('org_no'), function ($query) {
            $query->where('org_no', request()->get('org_no'));
        });
        $data->when(request()->get('email'), function ($query) {
            $email = request()->get('email');
            $query->where('email', "LIKE", "%{$email}%");
        });
        $data->when(request()->get('phone'), function ($query) {
            $phone = request()->get('phone');
            $query->where('phone', "LIKE", "%{$phone}%");
        });

        $showPerPage = request()->get('showPerPage');
        if ($showPerPage == "All") {
            return apiResponse('success', 200, $data->get());
        } else {
            $businesses = $data;
            return paginationResponse('success', 200, $businesses, $showPerPage);
        }
    }

    public function fetchAllBusinessRaw(): JsonResponse
    {
        $data = DB::table('users')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->leftJoin('user_information', 'user_information.user_id', '=', 'users.id')
            ->select(
                'users.name',
                'users.email',
                'users.user_status',
                'users.mobile',
                'users.image',
                'user_information.employee_id',
                'user_information.joining_date',
                'user_information.company_name',
                'user_information.company_name',
                'user_information.org_no',
                'user_information.vat_no',
                'user_information.contact_person',
                'user_information.business_type',
                'user_information.website_url',
                'user_information.phone',
                'user_information.company_email',
                'user_information.logo',
                'user_information.street',
                'user_information.city',
                'user_information.zip_code',
            );
        $data->where('roles.type', "BUSINESS");
        //Users filter
        $data->when(request()->get('name'), function ($query) {
            $name = request()->get('name');
            $query->where('users.name', "LIKE", "%{$name}%");
        });
        $data->when(request()->get('username'), function ($query) {
            $username = request()->get('username');
            $query->where('users.username', "LIKE", "%{$username}%");
        });
        $data->when(request()->get('email'), function ($query) {
            $email = request()->get('email');
            $query->where('users.email', "LIKE", "%{$email}%");
        });
        $data->when(request()->get('mobile'), function ($query) {
            $mobile = request()->get('mobile');
            $query->where('users.mobile', "LIKE", "%{$mobile}%");
        });
        $data->when(request()->get('is_active'), function ($query) {
            $query->where('users.is_active', request()->get('is_active'));
        });

        // User information filter
        $data->when(request()->get('employee_id'), function ($query) {
            $query->where('user_information.employee_id', request()->get('employee_id'));
        });
        $data->when(request()->get('company_name'), function ($query) {
            $company_name = request()->get('company_name');
            $query->where('user_information.company_name', "LIKE", "%{$company_name}%");
        });
        $data->when(request()->get('org_no'), function ($query) {
            $query->where('user_information.user_information.org_no', request()->get('org_no'));
        });
        $data->when(request()->get('vat_no'), function ($query) {
            $query->where('user_information.vat_no', request()->get('vat_no'));
        });
        $data->when(request()->get('contact_person'), function ($query) {
            $query->where('user_information.contact_person', request()->get('contact_person'));
        });
        $data->when(request()->get('business_type'), function ($query) {
            $query->where('user_information.business_type', request()->get('business_type'));
        });
        $data->when(request()->get('website_url'), function ($query) {
            $query->where('user_information.website_url', request()->get('website_url'));
        });
        $data->when(request()->get('phone'), function ($query) {
            $phone = request()->get('phone');
            $query->where('user_information.phone', "LIKE", "%{$phone}%");
        });
        $data->when(request()->get('company_email'), function ($query) {
            $company_email = request()->get('company_email');
            $query->where('user_information.company_email', "LIKE", "%{$company_email}%");
        });
        $data->when(request()->get('street'), function ($query) {
            $street = request()->get('street');
            $query->where('user_information.street', "LIKE", "%{$street}%");
        });
        $data->when(request()->get('city'), function ($query) {
            $city = request()->get('city');
            $query->where('user_information.city', "LIKE", "%{$city}%");
        });
        $data->when(request()->get('zip_code'), function ($query) {
            $query->where('user_information.zip_code', request()->get('zip_code'));
        });

        $showPerPage = request()->get('showPerPage');
        if ($showPerPage == "All") {
            return apiResponse('success', 200, $data->get());
        } else {
            $businesses = $data;
            return paginationResponse('success', 200, $businesses, $showPerPage);
        }
    }

    public function fetchAllBusiness(): JsonResponse
    {
        $data = User::query();
        $data->latest();
        $data->with(['role_info', 'user_information' => function ($query) {
            $query->businessSelectedFields();
        }]);
        $data->whereHas('role_info', function (Builder $query) {
            $query->where('type', 'BUSINESS');
        });
        $data->selectedFields();
        // Users filter
        $data->when(request()->get('name'), function ($query) {
            $name = request()->get('name');
            $query->where('name', 'LIKE', "%{$name}%");
        });
        $data->when(request()->get('user_status'), function ($query) {
            $user_status = request()->get('user_status');
            $query->where('user_status', 'LIKE', "%{$user_status}%");
        });
        $data->when(request()->get('company_name'), function ($query) {
            $company_name = request()->get('company_name');
            $query->whereHas('user_information', function ($query) use ($company_name) {
                $query->where('company_name', 'LIKE', "%{$company_name}%");
            });
        });
        $data->when(request()->get('username'), function ($query) {
            $username = request()->get('username');
            $query->where('username', 'LIKE', "%{$username}%");
        });
        $data->when(request()->get('email'), function ($query) {
            $email = request()->get('email');
            $query->where('email', 'LIKE', "%{$email}%");
        });
        $data->when(request()->get('mobile'), function ($query) {
            $mobile = request()->get('mobile');
            $query->where('mobile', 'LIKE', "%{$mobile}%");
        });

        if (request()->has('is_active')) {
            $data->where('is_active', request()->get('is_active'));
        }
        if (request()->has('role_id')) {
            $data->where('role_id', request()->get('role_id'));
        }
        // User information filter
        $data->when(request()->get('employee_id'), function ($query) {
            $query->whereHas('user_information', function ($query) {
                $query->where('employee_id', request()->get('employee_id'));
            });
        });
        $data->when(request()->get('company_name'), function ($query) {
            $company_name = request()->get('company_name');
            $query->whereHas('user_information', function ($query) use ($company_name) {
                $query->where('company_name', 'LIKE', "%{$company_name}%");
            });
        });
        $data->when(request()->get('org_no'), function ($query) {
            $query->whereHas('user_information', function ($query) {
                $query->where('org_no', request()->get('org_no'));
            });
        });
        $data->when(request()->get('vat_no'), function ($query) {
            $query->whereHas('user_information', function ($query) {
                $query->where('vat_no', request()->get('vat_no'));
            });
        });
        $data->when(request()->get('contact_person'), function ($query) {
            $query->whereHas('user_information', function ($query) {
                $query->where('contact_person', request()->get('contact_person'));
            });
        });
        $data->when(request()->get('business_type'), function ($query) {
            $query->whereHas('user_information', function ($query) {
                $query->where('business_type', request()->get('business_type'));
            });
        });
        $data->when(request()->get('website_url'), function ($query) {
            $query->whereHas('user_information', function ($query) {
                $query->where('website_url', request()->get('website_url'));
            });
        });
        $data->when(request()->get('phone'), function ($query) {
            $phone = request()->get('phone');
            $query->whereHas('user_information', function ($query) use ($phone) {
                $query->where('phone', 'LIKE', "%{$phone}%");
            });
        });
        $data->when(request()->get('company_email'), function ($query) {
            $company_email = request()->get('company_email');
            $query->whereHas('user_information', function ($query) use ($company_email) {
                $query->where('company_email', 'LIKE', "%{$company_email}%");
            });
        });
        $data->when(request()->get('street'), function ($query) {
            $street = request()->get('street');
            $query->whereHas('user_information', function ($query) use ($street) {
                $query->where('street', 'LIKE', "%{$street}%");
            });
        });
        $data->when(request()->get('city'), function ($query) {
            $city = request()->get('city');
            $query->whereHas('user_information', function ($query) use ($city) {
                $query->where('city', 'LIKE', "%{$city}%");
            });
        });
        $data->when(request()->get('zip_code'), function ($query) {
            $query->whereHas('user_information', function ($query) {
                $query->where('zip_code', request()->get('zip_code'));
            });
        });

        $data->when(request()->get('search'), function ($query) {
            $search = request()->get('search');
            $query->where(function ($query) use ($search) {
                $query->where('name', "LIKE", "%{$search}%")
                    ->orWhere('username', $search)
                    ->orWhere('email', "LIKE", "%{$search}%")
                    ->orWhere('mobile', $search);
            });
        });

        $showPerPage = request()->get('showPerPage');
        if ($showPerPage == "All") {
            return apiResponse('success', 200, $data->get());
        } else {
            return paginationResponse('success', 200, $data, request('showPerPage'));
        }
    }


    public function storeBusiness($data): JsonResponse
    {
        $user = new User();
        $user->role_id = $data->role_id;
        $user->username = $data->username;
        $user->name = $data->name;
        $user->email = $data->email;
        $user->mobile = $data->mobile;
        $user->password = Hash::make($data->password);
        $user->is_active = $data->is_active ?? true;
        $user->user_status = $data->user_status ?? "accept";

        if ($data->file('image')) {
            $user->image = uploadImage($data->file('image'), 'admin');
        }
        if ($user->save()) {
            // Store product against this business
            SetBusinessProduct::dispatch($user->id);

            $row = new UserInformation();
            $row->user_id = $user->id;
            $row->company_name = $data->company_name;
            $row->org_no = $data->org_no;
            $row->vat_no = $data->vat_no;
//            $row->contact_person = $data->contact_person;
//            $row->business_type = $data->business_type;
            $row->website_url = $data->website_url;
//            $row->phone = $data->phone;
            $row->company_email = $data->company_email;
            if ($data->file('logo')) {
                $row->logo = uploadImage($data->file('logo'), 'profile');
            }
            $row->street = $data->street;
            $row->city = $data->city;
            $row->zip_code = $data->zip_code;

            if ($row->save()) {
                return successMessageResponse('success', 200);
            } else {
                return failedResponse();
            }
        } else {
            return failedResponse();
        }

    }

    public function businessById($id): JsonResponse
    {
        $user = User::with(['role_info', 'user_information' => function ($query) {
            $query->businessSelectedFields();
        }])->whereHas('role_info', function (Builder $query) {
            $query->where('type', 'BUSINESS');
        })->selectedFields()->findOrFail($id);
        return apiResponse('success', 200, $user);
    }

    public function updateBusiness($data, $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $user->role_id = $data->role_id;
        $user->username = $data->username;
        $user->name = $data->name;
        $user->email = $data->email;
        $user->mobile = $data->mobile;
        //$user->password = Hash::make($data->password);
        $user->is_active = $data->is_active ?? true;
//        $user->user_status = $data->user_status??"";
        if ($data->file('image')) {
            $user->image = uploadImage($data->file('image'), 'admin');
        }
        if ($user->save()) {
            $row = UserInformation::where('user_id', $user->id)->firstOrFail();
            $row->user_id = $user->id;
            $row->company_name = $data->company_name;
            $row->org_no = $data->org_no;
            $row->vat_no = $data->vat_no;
            $row->contact_person = $data->contact_person;
            $row->business_type = $data->business_type;
            $row->website_url = $data->website_url;
            $row->phone = $data->phone;
            $row->company_email = $data->company_email;
            if ($data->file('logo')) {
                $row->logo = uploadImage($data->file('logo'), 'profile');
            }
            $row->street = $data->street;
            $row->city = $data->city;
            $row->zip_code = $data->zip_code;

            if ($row->save()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Information has been updated successfully!'
                ]);
            } else {
                return failedResponse();
            }
        } else {
            return failedResponse();
        }
    }

    public function deleteBusiness(int $id): JsonResponse
    {
        $business = User::findOrFail($id);

        if ($business->delete()) {
            return deleteResponse('success', 200);
        } else {
            return failedResponse();
        }
    }

    public function changeBusinessStatus(int $id): JsonResponse
    {
        $user = User::business()->findOrFail($id);
        $user->is_active = $user->is_active == 1 ? 0 : 1;
        $user->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Business status has been changed successfully!'
        ]);
    }

    public function changeBusinessPassword($requestData, $id): JsonResponse
    {
        $user = User::business()->findOrFail($id);
        $user->password = Hash::make($requestData->confirm_password);
        $user->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Business password has been changed successfully!'
        ]);
    }

    public function allBusiness()
    {
        $data = User::business()->accept()->active()->with([
            'user_information' => function ($query) {
                $query->businessSelectedFields();
            }
        ])->select('id', 'name')->get();
        return apiResponse('success', 200, $data);
    }
}
