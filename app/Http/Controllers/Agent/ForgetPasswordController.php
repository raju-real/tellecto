<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PasswordChangeRequest;
use App\Http\Requests\Agent\PassResendLinkSendRequest;
use App\Services\Agent\ForgetPasswordService;

class ForgetPasswordController extends Controller
{
    protected ForgetPasswordService $forgetPasswordService;

    public function __construct(ForgetPasswordService $forgetPasswordService)
    {
        $this->forgetPasswordService = $forgetPasswordService;
    }

    public function sendPasswordResetLink(PassResendLinkSendRequest $request)
    {
        try {
            return $this->forgetPasswordService->sendPasswordResetLink($request);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function passwordResetForm()
    {
        try {
            return $this->forgetPasswordService->passwordResetForm();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function resetPassword(PasswordChangeRequest $request)
    {
        try {
            return $this->forgetPasswordService->resetPassword($request);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }
}
