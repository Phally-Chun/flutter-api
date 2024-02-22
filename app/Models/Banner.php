<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Traits\DataScope;

class Banner extends Model
{
    use HasFactory, HasUuids, DataScope ;
    protected $fillable = [
        'title',
        'description',
        'image',
        'status',
    ];
}
