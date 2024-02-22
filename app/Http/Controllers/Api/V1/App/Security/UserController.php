<?php

namespace App\Http\Controllers\Api\V1\App\Security;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;
use App\Http\Resources\Api\V1\App\Security\UserResource;
use App\Http\Resources\Api\V1\App\Security\RoleResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Requests\Api\V1\App\Security\ChangePasswordRequest;
use App\Http\Requests\Api\V1\App\Security\UserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    // Get user with pagination
    public function index(Request $request)
    {
        $per_page = 15;
        if ($request->per_page) {
            $per_page = $request->per_page > 500 ? 500 : $request->per_page;
        }

        if($request->search === 'null'){
            $request['search'] = null;
        }

        $data = User::where(function ($q) use ($request) {
            if ($request->search) {
                $q->where('name', 'LIKE', '%' . $request->search . '%');
            }

            if ($request->name) {
                $q->where('name', 'like', '%' . $request->name . '%');
            }

            if ($request->status) {
                $q->where('status', $request->status);
            }
        })
            ->exceptRoot()
            ->exceptCurrentUser()
            ->notDelete()->orderBy('created_at', 'desc')
            ->paginate($per_page);

        $resource = UserResource::collection($data)->response()->getData(true);

        return response()->json([
            'data' => $resource['data'],
            'meta' => [
                'current_page' => $resource['meta']['current_page'],
                'last_page' => $resource['meta']['last_page'],
                'total' => $resource['meta']['total'],
            ]
        ]);
    }

    // Create new user
    public function store(UserRequest $request)
    {
        if($request->email === 'null' || $request->image === 'null'){
            $request['email'] = null;
            $request['image'] = null;
        }

        $findDeletedUser = User::where('username', $request->username)
                        ->where('status', 3)
                        ->first();
        
        if($findDeletedUser){
            $findDeletedUser->update([
                'name' => $request->name,
                'email' => $request->email,
                'image' => $request->image ? uploadImage($request->image, 'images/users/profile/') : null,
                'password' => Hash::make($request->password),
                'status' => $request->status,
            ]);
                    
            UserRole::where('user_id', $findDeletedUser->id)->delete();

            $userRoles = [];
            foreach ($request->roles as $role) {
                $userRoles[] = [
                    'id' => Str::uuid()->toString(),
                    'role_id' => $role,
                    'user_id' => $findDeletedUser->id
                ];
            }
            UserRole::insert($userRoles);

            return response()->json([
                'success' => true,
                'message' => "User have been created."
            ]);
        }

        $lastId = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'image' => $request->image ? uploadImage($request->image, 'images/users/profile/') : null,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'status' => $request->status,
        ])->id;

        $userRoles = [];
        foreach ($request->roles as $role) {
            $userRoles[] = [
                'id' => Str::uuid()->toString(),
                'role_id' => $role,
                'user_id' => $lastId
            ];
        }

        UserRole::insert($userRoles);

        return response()->json([
            'success' => true,
            'message' => "User have been created."
        ]);
    }

    // Get user by id
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => "User not found."
            ]);
        }

        $roles = Role::whereIn('id', $user->roles->pluck('id'))->get();

        return response()->json([
            'user' => new UserResource($user),
            'roles' => RoleResource::collection($roles),
        ]);
    }

    // Update user by id
    public function update(UserRequest $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            $status["success"] = false;
            $status["message"] = "User not found.";
            return response()->json($status);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'status' => $request->status,
        ]);

        UserRole::where('user_id', $user->id)->delete();
        $userRoles = [];
        foreach ($request->roles as $role) {
            $userRoles[] = [
                'id' => Str::uuid()->toString(),
                'role_id' => $role,
                'user_id' => $user->id
            ];
        }
        UserRole::insert($userRoles);

        return response()->json([
            'success' => true,
            'data' =>  $this->show($user->id)->getData(true),
            'message' => "User have been updated."
        ]);
    }

    public function updateUserProfile(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            $status["success"] = false;
            $status["message"] = "User not found.";
            return response()->json($status);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'username' => $request->username,
            'gender' => $request->gender,
        ]);

        return response()->json([
            'success' => true,
            'data' =>  $this->show($user->id)->getData(true),
            'message' => "User have been updated."
        ]);
    }

    public function changePassword(ChangePasswordRequest $request, $userId): \Illuminate\Http\JsonResponse
    {
        $user = User::find($userId);

        if (!$user) {
            $status["success"] = false;
            $status["message"] = "User not found.";
            return response()->json($status);
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'The current password is not correct',
                'message_details' => [
                    'current_password' => [0 => 'The current password is not correct'],
                ],
                'data' => []
            ]);
        } else {
            $user->update(['password' => Hash::make($request->new_password)]);
            $status["success"] = true;
        }

        return response()->json([
            'success' => true,
            'message' => 'Password have been updated.',
            'data' => new UserResource($user)

        ]);
    }

    // Delete user by id
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            $status["success"] = false;
            $status["message"] = "User not found.";
            return response()->json($status);
        }

        $user->update([
            'status' => 3,
        ]);

        return response()->json([
            'success' => true,
            'message' =>  "User have been deleted."
        ]);
    }


    public function uploadProfile(Request $request, $userId){
        $user = User::find($userId);

        if (!$user) {
            $status["success"] = false;
            $status["message"] = "User not found.";
            return response()->json($status);
        }

        if (File::exists(public_path($user->image))) {
            File::delete(public_path($user->image));
        }

        $user->update([
            'image' => $request->image ? uploadImage($request->image, 'images/users/profile/') : null,
        ]);

        return response()->json([
            'success' => true,
            'message' =>  "Profile have been uploaded.",
            'data' => new UserResource($user)
        ]);
    }

    public function removeProfile($userId){
        $user = User::find($userId);

        if (!$user) {
            $status["success"] = false;
            $status["message"] = "User not found.";
            return response()->json($status);
        }

        File::delete(public_path($user->image));

        $user->update([
            'image' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' =>  "Profile have been deleted.",
            'data' => new UserResource($user)
        ]);
    }
}
