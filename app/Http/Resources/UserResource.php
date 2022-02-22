<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'surnames' => $this->surnames,
            'email'=> $this->email,
            'country'=> $this->country,
            'phone'=> $this->phone,
            'postal_code'=> $this->postal_code,
        ];
    }
}
