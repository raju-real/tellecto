<?php

namespace App\Http\Requests\Admin;

use App\Rules\MaxExcelRows;
use Illuminate\Foundation\Http\FormRequest;

class updateProductFromXlRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'xl_file' => 'required|mimes:xlsx,xlx,xls'
        ];
    }
}
