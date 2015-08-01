<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    protected $dateFormat = \DateTime::ISO8601;

    protected $appends = [
        'group_url',
        'url',
    ];

    protected $dates = [
        'deleted_at',
    ];

    protected $fillable = [
        'description',
        'end_date',
        'featured',
        'image',
        'location',
        'name',
        'recurrence',
        'short_description',
        'short_name',
        'start_date',
    ];

    protected $hidden = [
        'deleted_at',
        'group',
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
