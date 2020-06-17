<?php

namespace App\Http\Resources;

use App\FootballModel;
use App\Http\Controllers\TeamsController;
use App\ManagersModel;
use App\TeamsModel;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB as Database;

class FootballResource extends JsonResource
{
    private static object $response;

    public static function teamEvents($uid, $teamID): object
    {
        $collection = Database::table('teams')->orderByRaw('football_events.created_at DESC')
            ->join('football_events', 'teams.team_id', '=', 'football_events.team_id')
            ->join('managers', 'teams.owner', '=', 'managers.uid')
            ->select('football_events.*', 'teams.team_name', 'teams.owner', 'teams.team_id', 'teams.team_slug', 'managers.first_name', 'managers.last_name')
            ->where('football_events.team_id', $teamID)
            ->get();

        $compiledCollection = [];
        foreach ($collection as $event) {
            $data = [
                'event' => self::_compileEventData($event),
                'user_team' => [
                    'team' => $event->team_name,
                    'id' => $event->team_id,
                    'slug' => $event->team_slug
                ],
                'challenger' => TeamsModel::select('team_id', 'team_name', 'team_slug')->where('team_id', $event->opponent)->first(),
                'manager' => ['first_name' => $event->first_name, 'last_name' => $event->last_name]
            ];
            $compiledCollection[] = $data;
        }

        self::$response = response()->json($compiledCollection);
        return self::$response;
    }

    private static function _compileEventData($event) {
        return [
            'activity_id' => $event->activity_id,
            'event_date' => $event->event_date,
            'created_at' => $event->created_at,
            'definition' =>$event->definition
        ];
    }

}
