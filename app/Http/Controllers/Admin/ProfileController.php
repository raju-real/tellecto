<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProfileRequest;
use App\Services\Admin\ProfileService;

class ProfileController extends Controller
{
    protected ProfileService $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;

    }
    public function adminProfile()
    {
        try {
            return $this->profileService->adminProfile();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function updateAdminProfile(ProfileRequest $request)
    {
     
        try {
            return $this->profileService->updateAdminProfile($request);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }
}
