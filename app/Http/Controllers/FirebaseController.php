<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
class FirebaseController extends Controller
{
    public function userSignin(Request $request) {
        $data = [
            'email' => $request->input('email'),
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'password' => $request->input('secure_password'),
            'confirmPassword' => $request->input('confirmPassword')
        ];
    }

}
