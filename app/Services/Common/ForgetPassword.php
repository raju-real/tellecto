<?php

namespace App\Services\Common;
use App\Models\PasswordResetToken;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use function Ramsey\Uuid\v1;

/**
 * Class ForgetPassword.
 */
class ForgetPassword
{
    public function sendPasswordResetLink(Request $request)
    {
        // Validate the request to ensure the email is provided and valid
        $request->validate(['email' => 'required|email']);

        // Find the agent by email
        $user = User::whereEmail($request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found!',
            ], 404);
        }

        // Generate a password reset token for the agent
        $token = Password::broker()->createToken($user);

        // Create the password reset URL
        $baseUrl = env('BACKEND_BASE_URL'); // Fetch the base URL from environment
        $password_reset_link = $baseUrl . '/reset-password?token=' . $token;

        // Prepare the email data
        $mail_data = [
            'activity_type' => 'password_reset_link',
            'view_file' => 'mail.layouts.app',
            'to_email' => $request->email,
            'to_name' => $user->name,
            'subject' => 'Reset Your Password for Tellecto.se',
            'password_resend_link' => $password_reset_link,
            'mail_body' => "Please click the below URL to reset your password",
        ];
        //return view('mail.common_mail_template',$mail_data);

        // Send the email with the reset link
        sendMail($mail_data);

        return response()->json([
            'status' => 'success',
            'message' => 'Password reset link has been sent successfully to your email!',
        ]);
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker('users'); // Use the 'agents' broker if you have a separate guard or broker for agents
    }

    public function passwordResetForm()
    {
        $token = request()->get('token');
        $passwordReset = PasswordResetToken::fetchOriginalToken($token);

        // If no matching token was found, or the token is expired, redirect with an error
        if (!$passwordReset || $this->tokenExpired($passwordReset->created_at)) {
            return redirect()->route('password.request')->withErrors(['token' => 'This password reset token is invalid or has expired.']);
        }

        return view('password.user_password_reset', compact('token'));
    }

    /**
     * Determine if the token has expired.
     *
     * @param string $createdAt
     * @return bool
     */
    protected function tokenExpired($createdAt)
    {
        $expires = config('auth.passwords.users.expire');
        return Carbon::parse($createdAt)->addMinutes($expires)->isPast();
    }

    public function resetPassword($requestData)
    {
        $token = $requestData->token;
        $passwordReset = PasswordResetToken::fetchOriginalToken($token);
        $email = $passwordReset->email;
        $user = User::whereEmail($email)->firstOrFail();
        $user->password = Hash::make($requestData->confirm_password);
        $user->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Password has been changed successfully!'
        ]);
    }
}
