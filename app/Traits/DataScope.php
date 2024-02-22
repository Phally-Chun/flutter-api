<?php

namespace App\Traits;
use Illuminate\Support\Facades\Auth;

trait DataScope
{
    /**
     * @param $q
     * @return mixed
     */
    public function scopeActive($q)
    {
        return $q->where('status', 1);
    }

    /**
     * @param $q
     * @return mixed
     */
    public function scopeInActive($q)
    {
        return $q->where('status', 2);
    }

    /**
     * @param $q
     * @return mixed
     */
    public function scopeDelete($q)
    {
        return $q->where('delete', 3);
    }

    /**
     * @param $q
     * @return mixed
     */
    public function scopeNotDelete($q)
    {
        return $q->where('status', '!=', 3);
    }

    /**
     * @param $q
     * @return mixed
     */
    public function scopeBranch($q)
    {
        if (!isSuperAdmin() && !isDeveloper()) {
            return $q->where('branch_id', auth()->user()->branch_id);
        }
    }

    // SCOPE USERDEVICE
    // public function scopeUserDevice($q)
    // {
    //     $q->whereNotNull('fcm_token');
    //     return $q;
    // }

    public function scopeDeliveryBranch($q)
    {
        if (!isSuperAdmin() && !isDeveloper()) {
            $q = $q->where('dest_branch', Auth::guard('admin-api')->user()->branch_id);
            $q = $q->orWhere('branch_id', Auth::guard('admin-api')->user()->branch_id);
            return $q;
        }
    }

    public function scopeTransferTo($q)
    {
        if (!isSuperAdmin() && !isDeveloper()) {
            return $q->where('dest_branch', Auth::guard('admin-api')->user()->branch_id);
        }
    }

    public function scopeTransferFrom($q)
    {
        if (!isSuperAdmin() && !isDeveloper()) {
            return $q->where('source_branch', Auth::guard('admin-api')->user()->branch_id);
        }
    }

    public function scopeTransferDelivery($q)
    {
        if (!isSuperAdmin() && !isDeveloper()) {
            return $q->where('dest_branch', '!=', Auth::guard('admin-api')->user()->branch_id);
        }
    }

    public function scopeRequestTransferDelivery($q)
    {
        if (!isSuperAdmin() && !isDeveloper()) {
            return $q->where('dest_branch', Auth::guard('admin-api')->user()->branch_id);
        }
    }

    public function scopeShop($q)
    {
        return $q->where('shop_id', Auth::guard('shop-api')->user()->id);
    }

    public function scopeDriver($q)
    {
        return $q->where('driver_id', Auth::guard('driver-api')->user()->id);
    }

    public function scopePickupPending($q)
    {
        return $q->where('pickup_status', 2);
    }

    public function scopePickupProcess($q)
    {
        return $q->where('pickup_status', 4);
    }

    public function scopePickupPackingBack($q)
    {
        return $q->where('pickup_status', 7);
    }

    public function scopePickupHistory($q)
    {
        return $q->where('history_type', 1);
    }

    public function scopeDeliveryHistory($q)
    {
        return $q->where('history_type', 2);
    }

    public function scopeDriverType($q)
    {
        $driver_type = Auth::guard('driver-api')->user()->driver_type;

        if ($driver_type == 1) {
            return $q->where('history_type', 1);
        } elseif ($driver_type == 2) {
            return $q->where('history_type', 2);
        } elseif ($driver_type == 3) {
            return $q->where('history_type', 1)->orWhere('history_type', 2);
        }
    }

    public function scopedefaultBranch($q)
    {
        if (!isSuperAdmin() && !isDeveloper()) {
            return $q->where('id', Auth::guard('admin-api')->user()->branch_id);
        }
    }

    public function scopeDriverPayment($q)
    {
        $q->where('driver_payment_status', 0)->where('driver_id', '!=' , 'null')->orWhere('driver_payment_status', 1);
        return $q;
    }

    public function scopeShopPayment($q)
    {
        if (!isSuperAdmin() && !isDeveloper()) {
            $q = $q->where('dest_branch', Auth::guard('admin-api')->user()->branch_id);
            $q = $q->orWhere('branch_id', Auth::guard('admin-api')->user()->branch_id);
        }
        $q->where('delivery_status', 5)
            ->where('driver_payment_status', 3)
            ->where('shop_payment_status', 0);

        return $q;
    }
}
