<?php

namespace App\Http\Controllers\Api\V1\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Http\Requests\Api\V1\Website\UserRequest;
use App\Http\Resources\Api\V1\Website\CustomerResource;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Current user login info
    public function getInfo()
    {
        $customer = Customer::find(Auth::guard('customer-api')->user()->id);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ]);
        }

        return response()->json([
            'success' => true,
            'user' => $customer
        ]);
    }

    // Upload profile
    public function uploadProfile(Request $request)
    {
        if (!$request->profile_image) {
            return response()->json([
                'success' => false,
                'message' => 'Image is required.'
            ], 400);
        }

        $driver = Driver::find(Auth::guard('driver-api')->user()->id);

        deletePath($driver->profile_image);

        $path = createPath('images/shops');

        $driver->update([
            'profile_image' => uploadImage($request->profile_image, $path)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profile have been uploaded.',
            'data' => $driver->profile_image
        ]);
    }

    // Remove profile
    public function removeProfile(Request $request)
    {
        $driver = Driver::find(Auth::guard('driver-api')->user()->id);

        if (!$driver->profile_image) {
            return response()->json([
                'success' => false,
                'message' => 'Profile already remove.'
            ], 400);
        }

        deletePath($driver->profile_image);

        $driver->update([
            'profile_image' => "",
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profile have been removed.',
            'data' => $driver->profile_image
        ]);
    }

    // Update Info
    public function updateInfo(UserRequest $request)
    {
        $driver = Driver::find(Auth::guard('driver-api')->user()->id);

        $gender = 3;
        if ($request->gender === "Female") {
            $gender = 1;
        } else if ($request->gender === "Male") {
            $gender = 2;
        }else{
            $gender = $driver->gender;
        }

        $driver->update([
            'name' => $request->name ? $request->name : $driver->name,
            'gender' => $gender,
            'password' => $request->password ? bcrypt($request->password) : $driver->password,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User info have been updated.',
            'data' => new DriverResource($driver)
        ]);
    }
}
