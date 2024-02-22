<?php

namespace App\Http\Controllers\Api\V1\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Meal;
use Illuminate\Support\Facades\File;



class MealController  extends Controller
{
    public function index(Request $request)
    {
        $meals = Meal::all();
        return response()->json([
            'success' => true,
            'message' => 'Success.',
            'data' => $meals,
        ]);
    }
}