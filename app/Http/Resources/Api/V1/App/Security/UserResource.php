<?php

namespace App\Http\Resources\Api\V1\Admin\Security;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Api\V1\Admin\Security\RoleResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'image' => $this->image,
            'username' => $this->username,
            'roles' => RoleResource::collection($this->roles),
            'status' => $this->status,
        ];
    }
}
