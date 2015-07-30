<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    protected $appends = [
        'group_url',
        'url',
    ];

    protected $hidden = [
        'deleted_at',
        'group',
    ];

    protected $dates = [
        'deleted_at',
    ];

    public function group()
    {
        return $this->belongsTo('App\Group');
    }

    public function getGroupUrlAttribute()
    {
        return $this->group->url;
    }

    public function getUrlAttribute()
    {
        return route('api.v1.events.show', ['id' => $this->id]);
    }
}
