<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * @method static Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder create(array $attributes = [])
 * @method public Builder update(array $values)
 */
class TeamsModel extends Model
{
    public $table = "teams";
    protected $fillable = [
        'uid',
        'owner',
        'team_name',
        'team_slug',
        'team_id',
        'sports_category',
        'active',
        'sports_type',
        'created_at',
        'updated_at',
    ];
}
