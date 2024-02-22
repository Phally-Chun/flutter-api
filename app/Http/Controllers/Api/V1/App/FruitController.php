<?php

namespace App\Http\Controllers\Api\V1\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fruit;
use App\Http\Resources\Api\V1\App\FruitResource;
use App\Http\Requests\Api\V1\App\FruitRequest;
use Illuminate\Support\Facades\File;



class FruitController  extends Controller
{
    // public function store
    public function index(Request $request)
    {
        $fruits = Fruit::all();
        return response()->json([
            'success' => true,
            'message' => 'Success.',
            'data' => FruitResource::collection($fruits),
        ]);
    }

    

    public function store(FruitRequest $request)
    {
        Fruit::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => uploadImage($request->image, 'images/fruits'),
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' =>  "Fruit have been created."
        ]);
    }

    public function uploadImage(Request $request, $id)
    {
        $fruit = Fruit::find($id);

        if (!$fruit) {
           $status ["success"] = false;
              $status ["message"] = 'Fruit not found.';
              return response()->json($status);
        }
    
        if (!$request->image) {
           if (File::exists(public_path($fruit->image))) {
                File::delete(public_path($fruit->image));
           }
        }

        return response()->json([
            'success' => true,
            'message' => 'Fruit have been upload.',
            'data' => new FruitResource($fruit)
        ]);
    }
}