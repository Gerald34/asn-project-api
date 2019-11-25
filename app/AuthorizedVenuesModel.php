<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuthorizedVenuesModel extends Model
{
    public $table = "authorized_venues";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'venue_id',
        'venue_name',
        'venue_coordinates',
        'venue_province',
        'created_at',
        'updated_at'
    ];
}
