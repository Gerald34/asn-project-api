<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\UserLoginModel as Users;
use App\TeamsModel as Team;
use App\UserFollowersModel;
class UserInformationResource extends JsonResource
{
    private static $information;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    public static function getInformation($uid) {
        return self::$information = [
            'personal' => self::_personalData($uid),
            'team' => [
                'institution' => self::_teamData($uid),
                'sports' => self::_getSportsType($uid)
            ],
            'fans' => [
                'fans_data' => self::_fanData($uid),
                'fan_count' => self::_fanCount($uid),
                'as_fan' => self::_following($uid)
            ]
        ];

    }

    private static function _personalData($uid) {
        return Users::select('uid', 'email', 'first_name', 'last_name', 'verification', 'disabled', 'created_at')
            ->where('uid', $uid)
            ->first();
    }

    private static function _teamData($uid) {
        $findTeam = Team::where('uid', $uid)->first();
        return (empty($findTeam)) ? $findTeam : ['response' => 'No team found, create team.'] ;
    }

    private static function _getSportsType($uid) {

    }

    private static function _fanData($uid) {
        $findFollowers = UserFollowersModel::where('uid', $uid)->get();
        return (empty($findFollowers)) ? $findFollowers : ['response' => 'No followers.'] ;
    }

    private static function _fanCount($uid) {
        return count(UserFollowersModel::where('uid', $uid)->get());
    }

    private static function _following($uid) {
        return count(UserFollowersModel::where('follower_id', $uid)->get());
    }

}
