<?php

namespace App\Http\Controllers\Api\V1\App;

use App\Models\Setting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class SettingController extends Controller
{
    public function  index(Request $request) {
        $settings = Setting::all();
        return response()->json([
            'success' => true,
            'message' => 'Success.',
            'data' => $settings,
        ]);
    }
}
