<?php

namespace App\Models;

use App\Traits\DataScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class Meal extends Model
{
    use HasFactory, HasUuids, DataScope;
    protected $table = 'meals';
    protected $fillable = [
        'name',
        'description',
        'image',
        'price',
    ];
}
