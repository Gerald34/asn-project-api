<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MyInformationModel extends Model
{
    public $table = "user_profile";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'uid',
        'current_city',
        'work_place',
        'education',
        'home_town',
        'biography',
        'updated_at'
    ];

}
