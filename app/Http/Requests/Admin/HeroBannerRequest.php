<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class HeroBannerRequest extends FormRequest
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
        $rules = [
            'title' => 'nullable|string|max:191',
            'link' => 'required|url|max:255',
            'banner_type' => 'nullable',
            'order_no' => 'nullable',
            'active_status' => 'required|in:0,1',
        ];

        $heroBannerId = $this->route('hero_banner');
        $imageIsRequired = $this->isMethod('post') || $this->isImageFieldNull($heroBannerId);

        if ($imageIsRequired) {
            $rules['image'] = 'required|image|mimes:jpg,jpeg,png|max:1024';
        } else {
            $rules['image'] = 'nullable|image|mimes:jpg,jpeg,png|max:1024';
        }

        return $rules;
    }

    /**
     * Check if the image field is null in the database for the given hero banner ID.
     *
     * @param int|null $heroBannerId
     * @return bool
     */
    protected function isImageFieldNull($heroBannerId): bool
    {
        if ($heroBannerId) {
            return DB::table('hero_banners')
                    ->where('id', $heroBannerId)
                    ->value('image') === null;
        }

        return false;
    }
}


