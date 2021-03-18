<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VerificationModel extends Model
{
    public $table = "account_verification";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uid',
        'verification_code',
        'created_at',
        'updated_at'
    ];
}
