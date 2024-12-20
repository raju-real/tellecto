<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserInformation extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            $this->mergeWhen(isBusiness(), [
                'company_name' => $this->company_name,
                'org_no' => $this->org_no,
                'vat_no' => $this->vat_no,
                'contact_person' => $this->contact_person,
                'business_type' => $this->business_type,
                'website_url' => $this->website_url,
                'phone' => $this->phone,
                'email' => $this->email,
                'logo' => $this->logo,
                'street' => $this->street,
                'city' => $this->city,
                'zip_code' => $this->zip_code,
            ]),
            $this->mergeWhen(isSuper() || isAdmin(), [
                'employee_id' => $this->employee_id,
                'joining_date' => $this->joining_date != Null ? date('Y-m-d', strtotime($this->joining_date)) : Null
            ]),
        ];
    }
}
