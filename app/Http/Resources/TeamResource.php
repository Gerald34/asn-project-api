<?php

namespace App\Http\Resources;

use App\Http\Resources\FirebaseResource;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use App\TeamModel;
use App\MembersModel;
class TeamResource extends JsonResource
{

    public static function createNewTeam($teamData) {
        TeamModel::insert([
            'uid' => $teamData['uid'],
            'owner' => $teamData['owner'],
            'team_id' => $teamData['team_id'],
            'team_name' => $teamData['team_name'],
            'sports_category' => $teamData['sports_category'],
            'team_slug' => $teamData['team_slug'],
            'active' => $teamData['active'],
            'created_at' => $teamData['created_at'],
            'updated_at' => $teamData['updated_at']
        ]);

        return FirebaseResource::teams($teamData);
    }

    public static function getTeamByUID($uid) {
        return TeamModel::where('uid', $uid)->first();
    }

    public static function joinTeam($uid, $teamUid) {
        MembersModel::insert([
            'uid' => $uid,
            'team_id' => $teamUid,
            'active' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        return ['response' => 'success'];
    }


}
