<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivitiesModel extends Model
{
    public string $table = "activities";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected array $fillable = [
        'id',
        'activity_id',
        'uid',
        'current_team_id',
        'challenger_id',
        'venue',
        'match_date',
        'created_at',
        'updated_at'
    ];

}
