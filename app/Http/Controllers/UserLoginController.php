<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserLoginResource;
use App\Http\Resources\FirebaseResource;

class UserLoginController extends Controller
{
    private $response;

    public function login(Request $request)
    {

        $authorize = FirebaseResource::login($request->input('userEmail'));
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
