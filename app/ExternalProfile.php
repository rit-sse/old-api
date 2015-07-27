<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExternalProfile extends Model
{
    protected $fillable = [
        'provider',
        'identifier',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'member_id',
        'updated_at',
    ];
}
