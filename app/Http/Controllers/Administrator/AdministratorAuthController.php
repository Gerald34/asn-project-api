<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Resources\FirebaseResource;
use App\Http\Resources\UserLoginResource;
use App\UserRolesModel;
use Illuminate\Http\Request;
use Psy\Exception\Exception;

class AdministratorAuthController extends Controller
{
    /**
     * @var \Illuminate\Http\JsonResponse
     */
    private static \Illuminate\Http\JsonResponse $response;

    /**
     * Firebase authentication first and fetching data from MySQL database on success
     * @param Request $request
     * @return object
     */
    public function login(Request $request): object
    {
        $email = trim(strip_tags($request->input('email')));
        $password = trim(strip_tags($request->input('password')));

        // Firebase authentication
        $authorize = FirebaseResource::login($email, $password);

        if (!isset($authorize['userInformation']['uid'])) {
            self::$response = response()->json(['error' => 401, 'message' => 'User not found, please create account'], 200);
        } else {
            // check if user email is verified
            if ($authorize['userInformation']['emailVerified'] !== true) {
                self::$response = response()->json(['error' => "Email not verified, Verification link sent to {$authorize['userInformation']['email']}."], 401);
            } else {
                $token = auth('api')->attempt($credentials = ['email' => $email, 'password' => $password]);
                try {
                    if (!$token) {
                        // authorize user credentials
                        self::$response = response()->json(['error' => 'unauthorized access', 'token' => $token], 401);
                    } else {
                        // update user login time
                        UserLoginResource::findUser($userData = [
                            'uid' => $authorize['userInformation']['uid'],
                            'last_login' => $authorize['userInformation']['lastLoginAt']
                        ]);
                        $role = UserRolesModel::where('uid', $authorize['userInformation']['uid'])->first();
                        $authorize['auth'] = ['token' => $token, 'token_type' => 'bearer', 'role' => $role->role];
                        self::$response = response()->json($authorize, 200);
                    }
                } catch (Exception $e) {
                    self::$response = response()->json(['response' => 'could not create token'], 500);
                }
            }
        }

        return self::$response;
    }
}
