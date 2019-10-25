<?php

namespace App\Http\Resources;

use App\Http\Resources\TeamResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Controllers\AvatarController;
use App\TeamModel;

class AppEngineResource extends JsonResource
{
    private static $response;
    public static function processRegisteredUserInformation($users) {

        $userData = [];
        foreach ($users as $user) {
            $userInformation = [
                'uid' => $user->uid,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'team' => self::getTeamInformation($user->uid)
            ];
            $userData[] = $userInformation;
        }

        self::$response = $userData;

        return self::$response;
    }

    private static function getTeamInformation($uid) {
        $teams = TeamModel::where('uid', $uid)->select('team_id', 'team_name')->first();
        if(!isset($teams)) {
            $teams = null;
        }

        return $teams;
    }

}
