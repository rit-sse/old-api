<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Mentor extends Model
{
    use SoftDeletes;

    protected $dateFormat = \DateTime::ISO8601;

    protected $appends = [
        'member_url',
        'url'
    ];

    protected $hidden = [
        'member',
    ];

    public function member()
    {
        return $this->belongsTo('App\Member');
    }

    public function getMemberUrlAttribute()
    {
        return $this->member->url;
    }

    public function getUrlAttribute()
    {
        return route('api.v1.mentors.show', ['id' => $this->id]);
    }
}
