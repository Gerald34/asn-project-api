<?php

namespace App\Http\Controllers;

use App\Http\Resources\FootballResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Resources\TeamResource;
use App\Http\Resources\GeneratorResource;

class TeamController extends Controller {
    public object $jsonResponse;

    public function create(Request $request) {
        $teamData = [
            'uid' => $request->input('userid'),
            'owner' =>  $request->input('userid'),
            'team_id' => GeneratorResource::generateRandomString(10),
            'team_name' => $request->input('teamName'),
            'sports_category' => $request->input('sports_category'),
            'team_slug' => GeneratorResource::generateSlugFromName($request->input('teamName')),
            'active' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];
        return TeamResource::createNewTeam($teamData);
    }

    public function getUserTeam($uid) {
        return TeamResource::getTeamByUID($uid);
    }

    public function JoinTeam($uid, $teamUid) {
        return TeamResource::joinTeam($uid, $teamUid);
    }

    public function getAllTeams() {
        return TeamResource::getTeams();
    }

    public function getCategories() {
        return TeamResource::getCategories();
    }

    public function getTeamEvents($uid, $teamID, $eventType): object {
        switch($eventType) {
            case 1:
                $this->jsonResponse = FootballResource::teamEvents($uid, $teamID);
                break;
            default:
                $this->jsonResponse = response()->json(['error' => 304, 'message' => 'Activity type not recognized']);
        }
        return $this->jsonResponse;
    }
}
