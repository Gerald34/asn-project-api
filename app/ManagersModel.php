<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManagersModel extends Model
{
    public string $table = "managers";
    protected array $fillable = [
        'id',
        'uid',
        'first_name',
        'last_name',
        'id_number',
        'passport',
        'created_at',
        'updated_at'
    ];
}
