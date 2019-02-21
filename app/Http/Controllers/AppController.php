<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class AppController
 * @package App\Http\Controllers
 */
class AppController extends Controller
{
    private $response;

    /**
     * @param $activityID
     * @return array
     */
    public function getAllTeams($activityID) {

        $teams = DB::table('registered_teams')
            ->where('activity_id', $activityID)
            ->get();

        $this->response = [
            'successCode' => 207,
            'allTeams' => $teams
        ];

        return $this->response;
    }

    /**
     * @return array
     */
    public function activities() {

        $activities = DB::table('sports_activities')->get();

        $this->response = [
            'successCode' => 207,
            'activities' => $activities
        ];

        return $this->response;
    }

    /**
     * @param $activityID
     * @return array
     */
    public function getPositionsByActivity($activityID) {

        $positions = DB::table('positions')
            ->where('activity_id', $activityID)
            ->get();

        $this->response = [
            'successCode' => 207,
            'positions' => $positions
        ];

        return $this->response;

    }
}
