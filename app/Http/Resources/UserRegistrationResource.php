<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
use App\UserLoginModel as Users;
use Carbon\Carbon;

/**
 * Class UserRegistrationResource
 * @package App\Http\Resources
 */
class UserRegistrationResource extends JsonResource
{
    private static $response;

    /**
     * @param $registration
     * @return array
     */
    public static function register($registration)
    {
        return self::saveRegistration($registration);
    }

    /**
     * @param $registration
     * @return array
     */
    private static function saveRegistration($registration)
    {
        $check = Users::where('email', $registration['email'])->first();
        if ($check !== null) {
            self::$response = ['errorCode' => 201, 'errorMessage' => 'User Exists'];
        } else {
            Users::create([
                'uid' => $registration['uid'],
                'email' => $registration['email'],
                'password' => $registration['passwordHash'],
                'verification' => $registration['verification'],
                'disabled' => $registration['disabled'],
                'first_name' => $registration['first_name'],
                'last_name' => $registration['last_name'],
                'updated_at' => $registration['created_at'],
                'created_at' => $registration['created_at']
            ]);
            $user = Users::select('uid', 'email', 'verification', 'disabled', 'first_name', 'last_name', 'created_at')
                ->where('uid', $registration['uid'])
                ->first();
            FirebaseResource::realtimeDatabase($user);
            self::$response = ['successCode' => 202, 'successMessage' => 'User Successfully Registered', 'user' => $user];
        }

        return self::$response;

    }

}
