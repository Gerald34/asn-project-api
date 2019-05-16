<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\TeamsResource;

class TeamsController extends Controller
{
    private $response;

    /**
     * Create team controller | Expected args { uid, team name, team sports category }
     * @param Request $request
     * @return array
     */
    public function create(Request $request) {

            $uid = trim(strip_tags($request->input('uid')));
            $team_name = strip_tags($request->input('team_name'));
            $sports_category = trim(strip_tags($request->input('sports_category')));

        $this->response = TeamsResource::CreateNewTeamDataCollection($uid, $team_name, $sports_category);
        return $this->response;
    }

    /**
     * Get team by owner uid
     * @param $uid
     * @return array
     */
    public function getTeam($uid) {
        return TeamsResource::findTeamByUID($uid);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function editTeamByOwnership(Request $request) {
        $data = [
            'uid' => trim(strip_tags($request->input('uid'))),
            'teamHash' => trim(strip_tags($request->input('teamHash'))),
            'team_name' => strip_tags($request->input('team_name')),
            'sports_category' => trim(strip_tags($request->input('sports_category')))
        ];
        return TeamsResource::editByOwner($data);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function removeExistingTeamByUID(Request $request) {
        $uid = $request->input('uid');
        $teamHash = $request->input('teamHash');
        return TeamsResource::findAndRemoveTeamByOwnership($uid, $teamHash);
    }

    public function joinTeam(Request $request) {
        $data = [
            'uid' => trim(strip_tags($request->input('uid'))),
            'team_id' => trim(strip_tags($request->input('team_id'))),
        ];

        return TeamsResource::joinSelectedTeam($data);
    }
}

