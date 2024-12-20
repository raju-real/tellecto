<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agent\AgentPasswordRequest;
use App\Http\Requests\Agent\ProfileRequest;
use App\Services\Agent\ProfileService;

class ProfileController extends Controller
{

    protected ProfileService $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;

    }

    public function agentProfile()
    {
        try {
            return $this->profileService->agentProfile();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function updateAgentProfile(ProfileRequest $request)
    {
        try {
            return $this->profileService->updateAgentProfile($request);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function changePassword(AgentPasswordRequest $request)
    {
        try {
            return $this->profileService->changePassword($request);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }


}
