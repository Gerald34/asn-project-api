<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AvatarModel extends Model
{
    public string $table = "avatars";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected array $fillable = [
        'id',
        'uid',
        'avatar',
        'created_at',
        'updated_at'
    ];

}
