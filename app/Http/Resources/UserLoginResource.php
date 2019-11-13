<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Resources\Json\JsonResource;
use App\UserLoginModel;
use Illuminate\Support\Facades\Hash;

class UserLoginResource extends JsonResource
{

    /**
     * @var
     */
    private static $response;

    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    /**
     * @param array $userData
     * @return Model|Builder|object|null
     */
    public function findUser(array $userData)
    {
        UserLoginModel::where('uid', $userData['uid'])
            ->update(['last_login' => $userData['last_login']]);
        return UserLoginModel::select('uid')->where('uid', $userData['uid'])->first();
    }

}
