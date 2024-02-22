<?php
namespace App\Http\Controllers\Api\V1\Website;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\Api\V1\Website\AuthenticationRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Login
    public function login(AuthenticationRequest $request)
    {
        // dd($request->all());
        $request['status'] = 1;
        if (Auth::guard('customer')->attempt($request->only('username', 'password', 'status'))) {
            config(['auth.guards.api.provider' => 'customer']);
            $data = $this->getTokenAndRefreshToken($request->username, $request->password);
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
        $clientId = env('CUSTOMER_CLIENT_ID');
        $clientSecret = env('CUSTOMER_CLIENT_SECRETE');
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
        if(!$request->refresh_token){
            return response()->json([
                'success' => false,
                'message' => 'Refresh token is required.'
            ], 400);
        }

        $data = $this->getrefreshToken($request->refresh_token);
        if($data['access_token']){
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
        $clientId = env('CUSTOMER_CLIENT_ID');
        $clientSecret = env('CUSTOMER_CLIENT_SECRETE');

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
        if(Auth::guard('customer-api')->check()){
            $user = Auth::guard('customer-api')->user();
            $user->token()->revoke();
            Auth::guard('customer')->logout();

            return response()->json([
                'success' => true,
                'message' => 'Logged out.',
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'User already logout.',
            ]);
        }
    }
}
