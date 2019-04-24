<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LikesModel extends Model
{
    public $table = "post_likes";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'uid',
        'post_id',
        'liked_at'
    ];
}
