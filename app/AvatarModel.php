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
        'user_id',
        'avatar',
        'uploaded_date',
        'updated_at'
    ];

}
