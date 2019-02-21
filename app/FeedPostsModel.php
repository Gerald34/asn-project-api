<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeedPostsModel extends Model
{
    public $table = "posts";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'message',
        'image',
        'creation_date',
        'last_update'
    ];

}
