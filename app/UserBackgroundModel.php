<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBackgroundModel extends Model
{
        public string $table = "user_background";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected array $fillable = [
        'id',
        'uid',
        'background',
        'created_at',
        'updated_at'
    ];
}
