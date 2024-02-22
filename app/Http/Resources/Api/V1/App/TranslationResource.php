<?php

namespace App\Http\Resources\Api\V1\Driver;

use Illuminate\Http\Resources\Json\JsonResource;

class TranslationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $translateValue = json_decode($this->value, true);

        $data = "";
        if($request->locale){
            $data = isset($translateValue[$request->locale]) ? $translateValue[$request->locale] : $translateValue['en'];
        }else{
            $data = $translateValue['en'];
        }

        return [
            'translate_key' => $this->key,
            'translate_value' => $data
        ];
    }
}
