<?php

namespace App\Http\Controllers\Api\V1\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Beverages;
use Illuminate\Support\Facades\File;


class BeveragesController  extends Controller
{
    // public function store
    public function index(Request $request)
    {
        $beverages = Beverages::all();
        return response()->json([
            'success' => true,
            'message' => 'Success.',
            'data' => $beverages,
        ]);
    }
}