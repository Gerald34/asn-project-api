<?php

namespace App\Http\Resources;
use App\UserLoginModel;
use App\VerificationModel;
use Carbon\Carbon;
use App\AvatarModel;
use App\UserBackgroundModel;
use Kreait\Firebase\Exception\DatabaseException;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class UserRegistrationResource
 * @package App\Http\Resources
 */
class UserRegistrationResource implements JWTSubject {
    private static array $response;
    private static VerificationModel $verificationModel;
    private static UserLoginModel $userRepository;

    /**
     * UserRegistrationResource constructor.
     * @param VerificationModel $verificationModel
     * @param UserLoginModel $userRepository
     */
    public function __construct(
        VerificationModel $verificationModel,
        UserLoginModel $userRepository
    ) {
        self::$verificationModel = $verificationModel;
        self::$userRepository = $userRepository;
    }

    /**
     * @param array $registration
     * @param string $hashedPassword
     * @return array
     * @throws DatabaseException
     */
    public static function register(array $registration, string $hashedPassword) {
        return self::saveRegistration($registration, $hashedPassword);
    }

    /**
     * @param array $registration
     * @param string $hashedPassword
     * @return array
     * @throws DatabaseException
     */
    private static function saveRegistration(array $registration, string $hashedPassword): array {
        $check = self::$userRepository::where('email', $registration['email'])->first();
        if ($check !== null) {
            self::$response = ['errorCode' => 201, 'errorMessage' => 'User Exists'];
        } else {
            self::$userRepository::create([
                'uid' => $registration['uid'],
                'email' => $registration['email'],
                'password' => $hashedPassword,
                'verification' => $registration['verification'],
                'disabled' => $registration['disabled'],
                'first_name' => $registration['first_name'],
                'last_name' => $registration['last_name'],
                'updated_at' => Carbon::now(),
                'created_at' => $registration['created_at'],
            ]);
            // $token = auth()->fromUser($registration);
            // return $token;
            self::setDefaultAvatar($registration['uid'], 'default.jpg');
            self::_setDefaultBackground($registration['uid'], 'default.jpg');
            $user = self::$userRepository::select('uid', 'verification', 'first_name', 'last_name')
                ->where('uid', $registration['uid'])
                ->first();

            if ($user) {
                FirebaseResource::realtimeDatabase($user, 'users/' . $user->uid);
            }

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

    /**
     * @param string $uid
     * @param string $avatar
     */
    private static function setDefaultAvatar(string $uid, string $avatar): void {
        AvatarModel::create([
            'uid' => $uid,
            'avatar' => $avatar,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }

    /**
     * @param string $uid
     * @param string $background
     */
    private static function _setDefaultBackground(string $uid, string $background): void {
        UserBackgroundModel::create([
            'uid' => $uid,
            'background' => $background,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }

    /**
     * @param string $uid
     * @param string $activationCode
     * @return bool|string
     */
    public static function oneTimePinConfirmation(string $uid, string $activationCode): bool {
        $account = self::$verificationModel::where('uid', $uid)->first();
        return ($account != null && $account->verification_code === $activationCode) ?
            FirebaseResource::activate($uid) : false;
    }

    public static function removeFailedUser(string $uid) {

    }

    /**
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    }

    /**
     * @param $password
     */
    public function setPasswordAttribute($password) {
        if ( !empty($password) ) {
            $this->attributes['password'] = bcrypt($password);
        }
    }
}
