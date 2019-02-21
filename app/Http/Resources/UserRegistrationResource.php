<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\UserLoginModel;

/**
 * Class UserRegistrationResource
 * @package App\Http\Resources
 */
class UserRegistrationResource extends JsonResource
{
    private static $response;

    /**
     * @param $newStudent
     * @return array
     */
    public static function register($newStudent)
    {
        return self::saveStudent($newStudent);
    }

    /**
     * @param $newStudent
     * @return array
     */
    private static function saveStudent($newStudent)
    {

        $check = UserLoginModel::where('email', $newStudent['email'])->first();

        if ($check !== null) {
            self::$response = [
                'errorCode' => 201,
                'errorMessage' => 'User Exists'
            ];

        } else {

            $save = UserLoginModel::create([
                'email' => $newStudent['email'],
                'password' => $newStudent['password'],
                'first_name' => $newStudent['first_name'],
                'middle_name' => $newStudent['middle_name'],
                'last_name' => $newStudent['last_name'],
                'date_of_birth' => $newStudent['date_of_birth'],
                'sports' => $newStudent['sports'],
                'position' => $newStudent['position'],
                'gender' => $newStudent['gender'],
                'team_name' => $newStudent['team_name'],
                'school_name' => $newStudent['school_name'],
                'grade' => $newStudent['grade'],
                'user_type' => $newStudent['user_type'],
                'updated_at' => now(),
                'created_at' => now()
            ]);

            self::$response = [
                'successCode' => 202,
                'successMessage' => 'User Successfully Registered',
                'user' => $save
            ];
        }

        return self::$response;

    }

}
