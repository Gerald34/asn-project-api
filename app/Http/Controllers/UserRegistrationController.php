<?php

namespace App\Http\Controllers;

use App\Http\Resources\FirebaseResource;
use Illuminate\Http\Request;
use App\Http\Resources\UserRegistrationResource;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserRegistrationController
 * @package App\Http\Controllers
 */
class UserRegistrationController extends Controller
{
    private $response;

    /**
     * @param Request $request
     * @return array
     */
    public function register(Request $request) {
        $registration = [];
        if ($request->input('password') === $request->input('confirmPassword')) {
            $registration = [
                'email' => $request->input('email'),
                'password' => $request->input('password')
            ];
        } else {
            $this->response = [
                'errorCode' => 505,
                'errorMessage' => 'Passwords do not match...'
            ];
        }

        $firebase = FirebaseResource::signup($registration);

        if (isset($firebase->uid)) {
            $mysqlDatabaseUsers = [
                'uid' => $firebase->uid,
                'email' => $firebase->email,
                'passwordHash' => $firebase->passwordHash,
                'verification' => $firebase->emailVerified,
                'disabled' => $firebase->disabled,
                'created_at' => $firebase->metadata->createdAt,
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
            ];
            $registrationResource = new UserRegistrationResource($resource = ['file' => '../../../asn-sports-firebase-adminsdk-hjyvg-f95e677461.json']);
            $this->response = $registrationResource::register($mysqlDatabaseUsers);
        } else {
            $this->response = $firebase;
        }

        return $this->response;
    }
}
