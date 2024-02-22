<?php

namespace App\Http\Controllers\Api\V1\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Http\Resources\Api\V1\App\BannerResource;
use App\Http\Requests\Api\V1\App\BannerRequest;

class BannerController  extends Controller
{
   
    // public function store
    public function index(Request $request)
    {
        $banners = Banner::all();
        return response()->json([
            'success' => true,
            'message' => 'Success.',
            'data' => BannerResource::collection($banners),
        ]);
    }

    public function store(BannerRequest $request)
    {
        Banner::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => uploadImage($request->image, 'images/banners'),
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' =>  "Banner have been created."
        ]);
    }

    public function update(BannerRequest $request, $id)
    {
        $banner = Banner::find($id);
        if (!$banner) {
            return response()->json([
                'success' => false,
                'message' => 'Banner not found.'
            ]);
        }

        $banner->update([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $request->image ? uploadImage($request->image, 'images/banners') : $banner->image,
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' =>  "Banner have been updated."
        ]);
    }
}
