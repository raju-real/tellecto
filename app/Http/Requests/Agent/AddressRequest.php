<?php

namespace App\Http\Requests\Agent;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\UniqueAddressType;

class AddressRequest extends FormRequest
{
    protected $addressId;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->guard('agent')->check();
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Assuming you are using route model binding and the route parameter is `address`
        $this->addressId = $this->route('address') ? $this->route('address') : null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'address_type' => ['required', 'in:HOME,OFFICE,SHIPPING,BILLING', new UniqueAddressType($this->addressId)],
            'street' => 'required|max:100',
            'city' => 'required|max:100',
            'address' => 'required|max:255',
            'zip_code' => 'required|max:20',
            'status' => 'required|in:0,1'
        ];
    }
}
