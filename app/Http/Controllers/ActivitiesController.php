<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use App\Http\Resources\ActivitiesResource;
use Illuminate\Http\Response;

class ActivitiesController extends Controller
{
    /**
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function createActivity(Request $request) {
        $uid = strip_tags(trim($request->input('uid')));
        $current_team_id = strip_tags(trim($request->input('current_team_id')));
        $activity_date = strip_tags(trim($request->input('activity_date')));
        $venue = strip_tags(trim($request->input('venue')));
        $challenger_id = strip_tags(trim($request->input('challenger_id')));

        ActivitiesResource::createActivityCollection($uid, $current_team_id, $activity_date, $venue, $challenger_id);
        return response('Activity created', 201)->header('Content-Type', 'text/plain');
    }

    /**
     * @param $uid
     * @param $current_team_id
     * @return ResponseFactory|Response
     */
    public function getActivities($uid, $current_team_id) {
        return ActivitiesResource::getActivityCollectionByID($uid, $current_team_id);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function editActivity(Request $request) {
        $data = [
            'activity_id' => $request->input('activity_id'),
            'uid' => strip_tags(trim($request->input('uid'))),
            'current_team_id' => strip_tags(trim($request->input('current_team_id'))),
            'challenger_id' => strip_tags(trim($request->input('challenger_id'))),
            'match_date' => strip_tags(trim($request->input('activity_date'))),
            'venue' => strip_tags(trim($request->input('venue'))),
        ];

        $editable = array_filter($data);
        ActivitiesResource::editActivityInformation($editable);
        return response('Activity edited', 201)->header('Content-Type', 'text/plain');
    }

    public function deleteActivity($uid, $activity, $teamID) {
        return ActivitiesResource::delete($uid, $activity, $teamID);
    }
}
