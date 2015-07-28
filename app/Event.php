<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    protected $appends = [
        'url'
    ];

    protected $hidden = [
        'deleted_at',
    ];

    protected $dates = [
        'deleted_at',
    ];

    public function getUrlAttribute()
    {
        return route('api.v1.events.show', ['id' => $this->id]);
    }
}
