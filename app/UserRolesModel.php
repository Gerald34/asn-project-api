<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRolesModel extends Model
{
    public $table = "user_roles";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uid',
        'role',
    ];

}
