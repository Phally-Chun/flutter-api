<?php

namespace App\Http\Resources\Api\V1\App;

use Illuminate\Http\Resources\Json\JsonResource;

class FruitResource extends JsonResource
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
            'description' => $this->description, // Add this line
            'image' => $this->image,
            'price' => $this->price, // Add this line
            'status' => $this->status,
        ];
    }
}
