<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeamModel extends Model
{
    public $table = "teams";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'uid',
        'owner',
        'team_id',
        'team_name',
        'team_slug',
        'sports_category',
        'active',
        'created_at',
        'updated_at'
    ];

}
