<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserLoginResource;

class UserLoginController extends Controller
{
    //
    private $response;

    public function login(Request $request) {

        $studentData = [
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ];


        $this->response = UserLoginResource::findUser($studentData);

        return $this->response;
    }

    public function userRegistration(Request $request) {
        $studentData = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'date_of_birth' => $request->input('date_of_birth'),
            'sports' => $request->input('sports'),
            'position' => $request->input('position'),
            'gender' => $request->input('gender'),
            'team_name' => $request->input('team_name'),
            'school_name' => $request->input('school_name'),
            'grade' => $request->input('grade')
        ];
    }
}
