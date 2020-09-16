<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserFollowersModel extends Model
{
    public string $table = "user_followers";
    protected array $fillable = [
        'uid',
        'owner',
        'follower_id',
        'created_at',
        'updated_at',
    ];
}
