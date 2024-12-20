<?php

namespace App\Http\Requests\Agent;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AgentPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->guard('agent')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
//            'old_password' => ['required', function ($attribute, $value, $fail) {
//                // Check if the old password matches the current password
//                if (!Hash::check($value, Auth::user()->password)) {
//                    $fail('The old password is incorrect.');
//                }
//            }],
            'new_password' => ['required', 'min:6'],
            'confirm_password' => ['required', 'same:new_password'],
        ];
    }
}
