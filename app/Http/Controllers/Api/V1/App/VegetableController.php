<?php

namespace App\Http\Controllers\Api\V1\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vegetetable;
use Illuminate\Support\Facades\File;


class VegetableController extends Controller
{
    // public function get data in here 
    public function index(Request $request)
    {
        $vegetetables = Vegetetable::all();
        return response()->json([
            'success' => true,
            'message' => 'Success.',
            'data' => $vegetetables,
        ]);
    }
}