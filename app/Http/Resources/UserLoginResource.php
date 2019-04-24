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
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    public static function findUser($studentData)
    {
        return UserLoginModel::where('uid', $studentData['uid'])->first();
    }

}
