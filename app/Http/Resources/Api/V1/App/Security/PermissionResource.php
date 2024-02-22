<?php

namespace App\Http\Resources\Api\V1\Admin\Security;

use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
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
            'module' => $this->module,
            'permission_name' => $this->permission_name,
            'permission_slug' => $this->permission_slug,
            'status' => $this->status,
        ];
    }
}
