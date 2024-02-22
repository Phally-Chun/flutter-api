<?php

namespace App\Http\Controllers\Api\V1\App\Security;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\Api\V1\Admin\Security\AuthenticationRequest;
use App\Models\User;
use App\Models\UserDevice;
use App\Models\AdminNotification;
use App\Http\Resources\Api\V1\Admin\Security\RoleResource;
use App\Http\Resources\Api\V1\Admin\Security\UserResource;
use App\Http\Resources\Api\V1\Admin\Security\PermissionResource;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Login
    public function login(AuthenticationRequest $request)
    {
        $request['status'] = 1;
        if (Auth::guard('admin')->attempt($request->only('username', 'password', 'status'))) {
            config(['auth.guards.api.provider' => 'admin']);
            $data = $this->getTokenAndRefreshToken($request->username, $request->password);
            if($data['access_token']){
                $user = Auth::guard('admin')->user();
                if($user){
                    $userAgent = $request->header('User-Agent');
                    $userDevice = UserDevice::where('user_id', $user->id)
                                            ->where('user_agent', $userAgent)->first();
                    if(!$userDevice){
                        UserDevice::create([
                            'user_id' => $user->id,
                            'user_agent' => $userAgent
                        ]);
                    }
                }
            }
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect username or password.',
            ]);
        }
    }

    // Get Token and refresh token
    public function getTokenAndRefreshToken($username, $password)
    {
        $clientId = env('ADMIN_CLIENT_ID');
        $clientSecret = env('ADMIN_CLIENT_SECRETE');
        $response = [];
        $response = Http::asForm()->post(env('PASSPORT_PORT') . '/oauth/token', [
            'grant_type' => 'password',
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'username' => $username,
            'password' => $password,
            'scope' => '',
        ]);
        $result = $response ?
            json_decode((string) $response->getBody(), true) :
            [
                'message' => 'Client not found.'
            ];

        return $result;
    }

    // Refresh token
    public function refreshToken(Request $request){
        if(!$request->refreshToken){
            return response()->json([
                'success' => false,
                'message' => 'Refresh token is required.'
            ], 400);
        }

        $data = $this->getrefreshToken($request->refreshToken);
        if(isset($data['access_token']) && $data['access_token']){
            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $data['message']
        ], 401);
    }

    // Get refresh token
    public function getRefreshToken($refreshToken) {
        $clientId = env('ADMIN_CLIENT_ID');
        $clientSecret = env('ADMIN_CLIENT_SECRETE');
        $response = Http::asForm()->post(env('PASSPORT_PORT') . '/oauth/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'scope' => '',
        ]);
        $result = json_decode((string) $response->getBody(), true);
        return $result;
    }

    // Logout
    public function logout()
    {
        if(Auth::guard('admin-api')->check()){
            /** @var \App\Models\MyUserModel $user **/
            $user = Auth::guard('admin-api')->user();
            if($user){
                $user->token()->revoke();
            }
            Auth::guard('admin')->logout();
            return response()->json([
                'success' => true,
                'message' => 'logout.',
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'User already logout.',
            ]);
        }
    }

    // Current user login info
    public function getUserInfo(Request $request){
        $user = User::find(Auth::guard('admin-api')->user()->id);

        if(!$user){
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ]);
        }

        $permissions = [];
        foreach($user->roles as $role){
            $permissions = $role->permissions;
        }

        $userAgent = $request->header('User-Agent');
        $userDevice = UserDevice::where('user_id', $user->id)
                                ->where('user_agent', $userAgent)->first();
        $deviceId = '';
        if($userDevice){
            $deviceId = $userDevice->id;
        }

        $notifyBadge = AdminNotification::active()
            ->where('user_device_id', $deviceId)
            ->where('read', false)
            ->notDelete()
            ->count();

        return response()->json([
            'user' => new UserResource($user),
            'roles' => RoleResource::collection($user->roles),
            'permissions' =>  PermissionResource::collection($permissions),
            'notify_badge' => $notifyBadge
        ]);
    }
}
