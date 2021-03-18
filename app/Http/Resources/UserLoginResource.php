<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Resources\Json\JsonResource;
use App\UserLoginModel;
use Illuminate\Support\Facades\Hash;

class UserLoginResource {
    private static UserLoginModel $userRepository;

    public function __construct(UserLoginModel $userRepository) {
        // parent::__construct($resource);
        self::$userRepository = $userRepository;
    }

    /**
     * @param array $userData
     * @return Model|Builder|object|null
     */
    public static function findUser(array $userData) {
        self::$userRepository::where('uid', $userData['uid'])
            ->update(['last_login' => $userData['last_login']]);
        return self::$userRepository::select('uid')->where('uid', $userData['uid'])->first();
    }

}
