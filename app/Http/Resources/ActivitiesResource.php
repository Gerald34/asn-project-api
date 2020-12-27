<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\ActivitiesModel;
use Carbon\Carbon;
use Illuminate\Http\Response;

class ActivitiesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    /**
     * @param string $uid
     * @param string $current_team_id
     * @param string $activity_date
     * @param string $venue
     * @param string $challenger_id
     */
    public static function createActivityCollection(
        string $uid,
        string $current_team_id,
        string $activity_date,
        string $venue,
        string $challenger_id): void {

        // Generate activity id
        $activityID = GeneratorResource::generateRandomString(5);

        // Save activity data
        ActivitiesModel::insert([
            'activity_id' => $activityID,
            'uid' => $uid,
            'current_team_id' => $current_team_id,
            'challenger_id' => $challenger_id,
            'venue' => $venue,
            'match_date' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }

    /**
     * @param string $uid
     * @param string $current_team_id
     * @return ResponseFactory|Response
     */
    public static function getActivityCollectionByID(string $uid, string $current_team_id) {
        $collection =  ActivitiesModel::where([
                ['current_team_id', '=', $current_team_id]
            ]
        )->orderByRaw('created_at DESC')->get();

        return response($collection, 200)
            ->header('Content-Type', 'text/json');
    }

    public static function editActivityInformation(array $data): void {
        foreach ($data as $key => $value) {
            ActivitiesModel::where('activity_id', $data['activity_id'])->update([ $key => $value ]);
        }
    }

    public static function delete(string $uid, string $activity, $teamID) {
        ActivitiesModel::where([
            ['activity_id', '=', $activity]
        ])->delete();

        $collection =  ActivitiesModel::where([
                ['current_team_id', '=', $teamID]
            ]
        )->orderByRaw('created_at DESC')->get();

        return $collection;
    }
}
