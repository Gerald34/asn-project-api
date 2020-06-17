<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FootballModel extends Model
{
    //
    public string $table = "football_events";
    protected array $fillable = [
        'id',
        'activity_id',
        'team_id',
        'opponent',
        'team_manager',
        'event_date',
        'created_at',
        'updated_at'
    ];
}
