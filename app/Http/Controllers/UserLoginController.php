<?php

namespace App\Http\Controllers;

use App\UserRolesModel;
use Illuminate\Http\Request;
use App\Http\Resources\UserLoginResource;
use App\Http\Resources\FirebaseResource;
use Kreait\Firebase\Exception\AuthException;
use Kreait\Firebase\Exception\FirebaseException;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\UserLoginModel;
use Exception;

class UserLoginController extends Controller implements JWTSubject {
    private static object $response;
    private array $attributes;
    private FirebaseResource $firebaseResource;
    private UserLoginResource $userLoginResource;
    private UserRolesModel $userRoleRepository;
    protected UserLoginModel $userLoginRepository;

    /**
     * UserLoginController constructor.
     * @param FirebaseResource $firebaseResource
     * @param UserLoginResource $userLoginResource
     * @param UserRolesModel $userRoleRepository
     * @param UserLoginModel $userLoginRepository
     */
    public function __construct(
        FirebaseResource $firebaseResource,
        UserLoginResource $userLoginResource,
        UserRolesModel $userRoleRepository,
        UserLoginModel $userLoginRepository
    ) {
        $this->firebaseResource = $firebaseResource;
        $this->userLoginResource = $userLoginResource;
        $this->userRoleRepository = $userRoleRepository;
        $this->userLoginRepository = $userLoginRepository;
    }

    public function index() {
        return $this->userLoginRepository::all();
    }

    /**
     * Firebase authentication first and fetching data from MySQL database on success
     * @param Request $request
     * @return object
     * @throws AuthException
     * @throws FirebaseException
     */
    public function login(Request $request): object {
        $email = trim(strip_tags($request->input('email')));
        $password = trim(strip_tags($request->input('password')));

        // Firebase authentication
        $authorize = $this->firebaseResource::fetchUserInformation($email, $password);

        if (!isset($authorize['userInformation']['uid'])) {
            self::$response = response()->json(['error' => 401, 'message' => 'User not found, please create account'], 200);
        } else {
            // check if user email is verified
            if ($authorize['userInformation']['emailVerified'] !== true) {
                self::$response = response()->json(['error' => "Email not verified, Verification link sent to {$authorize['userInformation']['email']}."], 200);
            } else {
                $token = auth('api')->attempt($credentials = ['email' => $email, 'password' => $password]);
                try {
                    if (!$token) {
                        // authorize user credentials
                        self::$response = response()->json(['error' => true, 'message' => 'unauthorized access', 'token' => $token], 200);
                    } else {
                        // update user login time
                        $this->userLoginResource::findUser($userData = [
                            'uid' => $authorize['userInformation']['uid'],
                            'last_login' => $authorize['userInformation']['lastLoginAt']
                        ]);
                        $role = $this->userRoleRepository
                            ::where('uid', $authorize['userInformation']['uid'])
                            ->first();
                        $authorize['auth'] = [
                            'token' => $token,
                            'token_type' => 'bearer',
                            'role' => $role->role
                        ];
                        self::$response = response()->json($authorize, 200);
                    }
                } catch (Exception $e) {
                    self::$response = response()->json(['error' => true, 'response' => 'could not create token', 'message' => $e->getMessage()], 500);
                }
            }
        }

        return self::$response;
    }

    public function logout($token)
    {

    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function setPasswordAttribute($password)
    {
        if (!empty($password)) {
            $this->attributes['password'] = bcrypt($password);
        }
    }

}
