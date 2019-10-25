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
        // Firebase authentication
        $authorize = FirebaseResource::login($request->input('userEmail'));

        // Mysql authentication and fetch data
        if (isset($authorize->uid)) {
            $userData = [
                'uid' => $authorize->uid,
                'last_login' => $authorize->metadata->lastLoginAt
            ];
            $userInformation = UserLoginResource::findUser($userData);
            $this->response = [ 'successCode' => 201, 'userInformation' => $userInformation ];
        } else {
            $this->response = ['errorCode' => 401, 'errorMessage' =>  $authorize];
        }

        return $this->response;
    }

    public function logout($token) {

    }

}
