<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserLoginResource;

class UserLoginController extends Controller
{
    private $response;

    public function login(Request $request) {

        $studentData = [
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ];

        $this->response = UserLoginResource::findUser($studentData);

        return $this->response;
    }

}
