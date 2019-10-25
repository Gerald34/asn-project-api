<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
use App\UserLoginModel as Users;
use Carbon\Carbon;
use App\AvatarModel;
use App\UserBackgroundModel;
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
                'updated_at' => Carbon::now(),
                'created_at' => $registration['created_at'],
            ]);
            self::setDefaultAvatar($registration['uid'], 'default.jpg');
            self::_setDefaultBackground($registration['uid'], 'default.jpg');
            $user = Users::select('uid', 'verification', 'first_name', 'last_name')
                ->where('uid', $registration['uid'])
                ->first();
            FirebaseResource::realtimeDatabase($user);
            self::$response = [
                'successCode' => 202,
                'successMessage' => 'Profile created successfully.',
                'userInformation' => [
                    'uid' => $registration['uid']
                ]
            ];
        }

        return self::$response;
    }

    private static function setDefaultAvatar($uid, $avatar): void {
        AvatarModel::create([
            'uid' => $uid,
            'avatar' => $avatar,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }

    private static function _setDefaultBackground($uid, $background): void {
        UserBackgroundModel::create([
            'uid' => $uid,
            'background' => $background,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }

}
