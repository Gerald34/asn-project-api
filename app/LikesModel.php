<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LikesModel extends Model
{
    public string $table = "post_likes";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected array $fillable = [
        'id',
        'uid',
        'post_id',
        'liked_at'
    ];
}
