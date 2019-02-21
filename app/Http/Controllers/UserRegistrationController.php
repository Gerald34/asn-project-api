<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserRegistrationResource;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserRegistrationController
 * @package App\Http\Controllers
 */
class UserRegistrationController extends Controller
{
    /**
     * @param Request $request
     * @return array
     */
    public function register(Request $request) {
        $newStudent = [
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'date_of_birth' => $request->input('date_of_birth'),
            'sports' => $request->input('sports'),
            'position' => $request->input('position'),
            'gender' => $request->input('gender'),
            'team_name' => $request->input('team_name'),
            'school_name' => $request->input('school_name'),
            'grade' => $request->input('grade'),
            'user_type' => $request->input('user_type'),

        ];

        $register = UserRegistrationResource::register($newStudent);

        return $register;
    }
}
