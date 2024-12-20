<?php

namespace App\Services\Admin;

use App\Http\Resources\UserResource;
use App\Models\Admin;
use App\Models\Agent;
use App\Models\Business;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Class AuthService.
 */
class AuthService
{
    public function userLogin($request)
    {
        $data = $request->validated();

        $user = User::where([
            'email' => $data['email'],
            'is_active' => 1,
            'user_status' => 'accept'
        ])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect or the business is inactive.'],
            ]);
        }

        $userData = [
            'id' => $user->id,
            'role_info' => $user->role_info,
            'name' => $user->name,
            'email' => $user->email,
            'mobile' => $user->mobile,
            'username' => $user->username,
            'image' => $user->image,
            'user_information' => $user->user_information
        ];

        // Return response if user Business

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Logged in Successfully',
                'user' => $userData,
                'token' => $user->createToken('auth-user-token', ['role:user'])->plainTextToken
            ]);
//
//
//        // For other users
//        return response()->json([
//            'status' => 'success',
//            'code' => 200,
//            'message' => 'Logged in Successfully',
//            'user' => new UserResource($user),
//            'token' => $user->createToken('auth-user-token', ['role:user'])->plainTextToken
//        ]);
    }

    public function adminLogin($request): JsonResponse
    {
        $data = $request->validated();
        $field_name = filter_var($data['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $admin = Admin::where([$field_name => $data['email'], 'is_active' => 1])->first();

        if (!$admin || !Hash::check($data['password'], $admin->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }


        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Logged in Successfully',
            'guard' => 'admin',
            'user' => $admin,
            'token' => $admin->createToken('auth-admin-token', ['role:admin'])->plainTextToken
        ]);
    }

    //not used
    public function businessLogin($request): JsonResponse
    {
        $data = $request->validated();
        $business = Business::where([
            'email' => $data['email'],
            'status' => 1,
            'user_status' => "accept"
        ])->first();

        if (!$business || !Hash::check($data['password'], $business->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Logged in Successfully',
            'guard' => 'business',
            'user' => $business,
            'token' => $business->createToken('auth-business-token', ['role:business'])->plainTextToken
        ]);
    }

    public function agentLogin($request)
    {
        $data = $request->validated();

        $agent = Agent::where(['email' => $data['email'], 'status' => 1])->first();

        if (!$agent || !Hash::check($data['password'], $agent->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        //Cache::forget('agent_info');

        $auth_info = [
            'agent_id' => $agent->id,
            'business_id' => $agent->business_id,
            'user_type' => "Agent"
        ];
//        Cache::put('agent_info', $auth_info);

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Logged in Successfully',
            'guard' => 'agent',
            'user' => $agent,
            'token' => $agent->createToken('auth-agent-token', ['role:agent'])->plainTextToken
        ]);
    }
    //######################//
}
