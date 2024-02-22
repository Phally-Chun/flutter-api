<?php

namespace App\Models;

use App\Traits\DataScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Beverages extends Model
{
    use HasFactory ,HasUuids, DataScope ;
    protected $fillable = [
        'name',
        'image',
        'price',
        'status',
    ];
}
