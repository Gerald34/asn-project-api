<?php

namespace App\Http\Controllers;

use App\Http\Resources\FirebaseResource;
use App\Http\Resources\ProfileSetupResource;
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
                'email' => trim(strip_tags($request->input('email'))),
                'password' => trim(strip_tags($request->input('password'))),
                'first_name' => trim(strip_tags($request->input('first_name'))),
                'last_name' => trim(strip_tags($request->input('last_name')))
            ];
        } else {
            $this->response = [
                'errorCode' => 505,
                'errorMessage' => 'Passwords do not match...'
            ];
        }

        $resource = new FirebaseResource($request);
        $this->response = $resource->signup($registration);

        return $this->response;
    }

    public function profile($uid) {
        return ProfileSetupResource::newProfileSetup($uid);
    }

}
