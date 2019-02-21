<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\UserLoginModel;
use Illuminate\Support\Facades\Hash;

class UserLoginResource extends JsonResource
{

    /**
     * @var
     */
    private static $response;

    /**
     * @param $studentData
     * @return array
     */
    public static function findUser($studentData)
    {

        // return Hash::make($studentData['password']);

        $userInformation = UserLoginModel::where('email', $studentData['email'])->first();

        if (!empty($userInformation)) {

            if (Hash::check($studentData['password'], $userInformation->password)) {
                // Successful login
                switch ($userInformation) {
                    case $userInformation->user_type === 1:
                        self::$response = [
                            'successCode' => 201,
                            'successMessage' => 'Sign In Successful',
                            'url' => 'student',
                            'user' => $userInformation
                        ];
                        break;

                    case $userInformation->user_type === 2:
                        self::$response = [
                            'successCode' => 201,
                            'successMessage' => 'Sign In Successful',
                            'url' => 'coach',
                            'user' => $userInformation
                        ];
                        break;

                    case $userInformation->user_type === 3:
                        self::$response = [
                            'successCode' => 201,
                            'successMessage' => 'Sign In Successful',
                            'url' => 'referee',
                            'user' => $userInformation
                        ];
                        break;

                    case $userInformation->user_type === 4:
                        self::$response = [
                            'successCode' => 201,
                            'successMessage' => 'Sign In Successful',
                            'url' => 'parent',
                            'user' => $userInformation
                        ];
                        break;
                }

            } else {
                // Incorrect Password
                self::$response = [
                    'errorCode' => 406,
                    'errorMessage' => 'username and Password do not match'
                ];
            }

        } else {
            // User not found
            self::$response = [
                'errorCode' => 407,
                'errorMessage' => 'Account not found: '
            ];
        }


        return self::$response;
    }

}
