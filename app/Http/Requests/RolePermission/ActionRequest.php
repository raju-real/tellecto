<?php

namespace App\Http\Requests\RolePermission;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return isSuper();
    }

    public function rules(): array
    {
        $rule = [
            'name' => [
                'required',
                Rule::unique('actions')->whereNull('deleted_at')
                    ->when($this->isMethod('put'), function ($rule) {
                        $rule->ignore($this->route()->parameter('action'));
                    })
            ],

        ];

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rule['status'] = 'required|in:0,1';
        }

        return $rule;


    }
}
