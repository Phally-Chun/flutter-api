<?php

namespace App\Http\Resources\Api\V1\Admin\Security;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Api\V1\Admin\Security\PermissionResource;

class RoleResource extends JsonResource
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
            'description' => $this->description,
            'permissions' => PermissionResource::collection($this->permissions),
            'status' => $this->status,
        ];
    }
}
