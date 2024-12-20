<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Http\Requests\Business\ProfileRequest;
use App\Services\Business\ProfileService;

class ProfileController extends Controller
{
    protected ProfileService $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;

    }
    public function businessProfile()
    {
        try {
            return $this->profileService->businessProfile();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }
    public function updateBusinessProfile(ProfileRequest $request)
    {
        try {
            return $this->profileService->updateBusinessProfile($request);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }
}
