<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLoginModel extends Model
{
    public $table = "users";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
        'first_name',
        'middle_name',
        'last_name',
        'date_of_birth',
        'sports',
        'position',
        'gender',
        'team_name',
        'school_name',
        'grade',
        'creation_date',
        'last_update',
        'user_type',
        'student_name',
        'referee_number',
        'team'
    ];

}
