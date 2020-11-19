<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AvatarModel extends Model
{
    public $table = "avatars";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'uid',
        'avatar',
        'created_at',
        'updated_at'
    ];

}
