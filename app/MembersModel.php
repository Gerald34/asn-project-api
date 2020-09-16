<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * @method static Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder create(array $attributes = [])
 * @method public Builder update(array $values)
 */
class MembersModel extends Model
{
    public string $table = "team_members";
    public array $fillable = [
        'uid',
        'team_id',
        'active',
        'created_at',
        'updated_at',
    ];
}
