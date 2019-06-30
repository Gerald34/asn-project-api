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
     * @param $userData
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    public static function findUser($userData)
    {
        UserLoginModel::where('uid', $userData['uid'])
            ->update(['last_login' => $userData['last_login']]);
        return UserLoginModel::select('uid')->where('uid', $userData['uid'])->first();
    }

}
