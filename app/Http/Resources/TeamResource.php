<?php

namespace App\Http\Resources;

use App\Http\Resources\FirebaseResource;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use App\TeamModel;
use App\MembersModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class TeamResource extends JsonResource
{

    private static object $response;

    public static function createNewTeam($teamData) {
        $exists = TeamModel::where('uid', $teamData['uid'])->first();
        if ($exists === null) {
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
            FirebaseResource::teams($teamData);
            self::$response = response()->json([
                'responseCode' => 200,
                'data' => TeamModel::where('uid', $teamData['uid'])->get()
            ]);
        } else {
            self::$response = response()->json([
                'responseCode' => 304,
                'responseMessage' => 'A team with your user id already exists. Cannot create more than one team.'
            ]);
        }

        return self::$response;
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

    public static function getTeams() {
        return TeamModel::select('id', 'team_id', 'owner', 'team_name')->where('active', 1)->get();
    }

    public static function getCategories() {
        return DB::table('sports_category')->get();
    }

    /**
     * @param $teamID
     * @return \Illuminate\Http\Response|object|null
     */
    public static function getTeamFlag($teamID) {
        $display_image = DB::table('team_display_images')
            ->select('display_image')->where('team_id', $teamID)->first();
        if (empty($display_image)) {
            self::$response = null;
        } else {
            $path = storage_path('app/teams/' . $teamID . '/display_image/' . $display_image->display_image);
            if (!File::exists($path)) { abort(404); }
            $file = File::get($path);
            $type = File::mimeType($path);
            self::$response = Response::make($file, 200);
            self::$response->header("Content-Type", $type);
        }

        return self::$response;
    }

}
