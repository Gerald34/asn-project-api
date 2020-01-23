<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Query\Builder;

/**
 * @method static Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder create(array $attributes = [])
 * @method public Builder update(array $values)
 */
class MyInformationModel extends Model
{
    public string $table = "user_profile";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected array $fillable = [
        'id',
        'uid',
        'current_city',
        'work_place',
        'education',
        'home_town',
        'biography',
        'updated_at'
    ];

}
