<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoverImageModel extends Model
{
    public string $table = "cover_image";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected array $fillable = [
        'id',
        'uid',
        'cover_image',
        'created_at',
        'updated_at'
    ];
}