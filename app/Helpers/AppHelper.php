<?php

use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

// Is Super Admin
function isSuperAdmin()
{
    $roles = Auth::guard('admin-api')->user()->roles;
    $roleFilter = collect($roles)->where('name', 'Super Admin')->all();
    return count($roleFilter) > 0 ? true : false;
}

// Is Super Developer
function isDeveloper()
{
    $roles = Auth::guard('admin-api')->user()->roles;
    $roleFilter = collect($roles)->where('name', 'Developer')->all();
    return count($roleFilter) > 0 ? true : false;
}

function createPath($path)
{
    $paths = explode('/', $path);
    if (count($paths) > 0) {
        $newPath = '';
        foreach ($paths as $_path) {
            $newPath .= $_path . '/';
            if (!File::exists($newPath)) {
                File::makeDirectory($newPath);
            }
        }
        return $newPath;
    }
    return $path;
}

function deletePath($path)
{
    if (File::exists($path)) {
        File::delete($path);
    }
    return null;
}

function uploadImage($requestFile, $path)
{
    if ($requestFile) {
        $format = $requestFile->getClientOriginalExtension();
        $img = Image::make($requestFile)->resize(1280, 1280);
        $imgPath = $path . uniqid() . '.' . $format;
        $img->save($imgPath, 50);
    } else {
        $format = "";
        $imgPath = null;
    }
    return $imgPath;
}

function uploadTextEditorImage($requestFile, $path){
    if ($requestFile) {
        $uuid = uniqid();
        $format = $requestFile->getClientOriginalExtension();
        $img = Image::make($requestFile)->resize(1280, 1280);
        $imgPath = $path . $uuid . '.' . $format;
        $imgName = $uuid . '.' . $format;
        $img->save($imgPath, 50);
    } else {
        $imgPath = null;
    }
    return $imgName;
}





