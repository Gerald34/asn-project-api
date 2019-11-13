<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserLoginResource;
use App\Http\Resources\FirebaseResource;

class UserLoginController extends Controller
{
    private $response;

    /**
     * Firebase authentication first and fetching data from MySQL database on success
     * @param Request $request
     * @return array
     */
    public function login(Request $request)
    {
        $fireResource = new FirebaseResource($request);
        $loginResource = new UserLoginResource($request);
        $email = trim(strip_tags($request->input('userEmail')));
        $password = trim(strip_tags($request->input('userPassword')));
        // Firebase authentication
        $authorize = $fireResource->login($email, $password);

        // Mysql authentication and fetch data
        if (isset($authorize['userInformation']['uid'])) {
            $userData = [
                'uid' => $authorize['userInformation']['uid'],
                'last_login' => $authorize['userInformation']['lastLoginAt']
            ];
            $userInformation = $loginResource->findUser($userData);
            $this->response = $authorize;
        } else {
            $this->response = ['errorCode' => 401, 'errorMessage' =>  $authorize];
        }

        return $this->response;
    }

    public function logout($token) {

    }

}
