<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //$user_information=optional(new UserInformation($this->user_information));
        return [
            'id' => $this->id,
            'role_info' => $this->role_info,
            'name' =>  $this->name,
            'email' =>  $this->email,
            'mobile' =>  $this->mobile,
            'username' =>  $this->username,
            'image' =>  $this->image,
            'user_information' => new UserInformation($this->user_information)
        ];
    }
}
