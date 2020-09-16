<?php

namespace App\Http\Resources;

use App\FootballModel;
use App\TeamsModel;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB as Database;
use App\Http\Resources\MembersResource;

class FootballResource extends JsonResource {
    private static object $response;

    /**
     * @param string $uid
     * @return object
     */
    public static function teamEvents(string $uid): object {
        $userVerification = MembersResource::verifyUserMembership($uid);
        if(!empty($userVerification->team_id)):
            self::$response = self::_runActivityQuery(
                'football_events',
                'team_id',
                $userVerification->team_id
            );
        else:
            self::$response = response()->json(
                ['error' => 204, 'message' => 'Looks like you\'re not part a team']
            );
        endif;
        return self::$response;
    }

    /**
     * @param $activity
     * @return object
     */
    public static function getActivityData($activity):object {
        return self::_runActivityQuery('football_events','activity_id', $activity);
    }

    /**
     * @param string $table
     * @param string $column
     * @param string $value
     * @return object
     */
    private static function _runActivityQuery(string $table, string $column, string $value):object {
        $collection = Database::table('teams')->orderByRaw('football_events.created_at DESC')
            ->join(
                'football_events',
                'teams.team_id',
                '=',
                'football_events.team_id'
            )->join('managers', 'teams.owner', '=', 'managers.uid')
            ->select('football_events.*', 'teams.team_name', 'teams.owner', 'teams.team_id', 'teams.team_slug', 'managers.first_name', 'managers.last_name')
            ->where($table . '.' . $column, $value)->get();
        return self::_compileEventData($collection);
    }

    /**
     * @param object $collection
     * @return object
     */
    private static function _compileEventData(object $collection):object {
        $compiledCollection = [];
        foreach ($collection as $event) {
            $compiledCollection[] = [
                'event' => [
                    'activity_id' => $event->activity_id,
                    'event_date' => $event->event_date,
                    'created_at' => $event->created_at,
                    'definition' =>$event->definition
                ],
                'user_team' => [
                    'team' => $event->team_name,
                    'id' => $event->team_id,
                    'slug' => $event->team_slug
                ],
                'challenger' => TeamsModel::select('team_id', 'team_name', 'team_slug')->where('team_id', $event->opponent)->first(),
                'manager' => ['first_name' => $event->first_name, 'last_name' => $event->last_name]
            ];
        }
        return response()->json($compiledCollection);
    }

}
