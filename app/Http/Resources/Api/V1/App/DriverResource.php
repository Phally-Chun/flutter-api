<?php

namespace App\Http\Resources\Api\V1\Driver;

use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $gender = "N/A";
        if($this->gender === 1){
            $gender = "Female";
        }else if($this->gender === 2){
            $gender = "Male";
        }

        $commissionType = "";
        if($this->commission_type === 1){
            $commissionType = "Percentage";
        }else if($this->commission_type === 2){
            $commissionType = "Package";
        }

        $driverType = "All";
        if($this->driver_type === 1){
            $driverType = "Pick Up";
        }else if($this->driver_type === 2){
            $driverType = "Drop Off";
        }

        $status = "";
        if($this->status === 1){
            $status = "Active";
        }else if($this->status === 2){
            $status = "Inactive";
        }

        return [
            'id' => $this->id,
            'branch' => optional($this->branch)->name,
            'name' => $this->name,
            'profile_image' => $this->profile_image,
            'date_of_birth' => $this->date_of_birth,
            'join_date' => $this->join_date,
            'phone_number' => $this->phone_number,
            'driver_number' => $this->driver_number,
            'gender' => $gender,
            'username' => $this->username,
            'driver_type' => $driverType,
            'commission_type' => $commissionType,
            'status' => $status,
        ];
    }
}
