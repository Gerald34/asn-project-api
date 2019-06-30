<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserFollowersModel extends Model
{
    public $table = "user_followers";
    protected $fillable = [
        'uid',
        'owner',
        'follower_id',
        'created_at',
        'updated_at',
    ];
}
