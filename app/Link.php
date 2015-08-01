<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Link extends Model
{
    use SoftDeletes;

    protected $dateFormat = \DateTime::ISO8601;

    protected $dates = [
        'deleted_at',
    ];

    protected $fillable = [
        'expanded_link',
        'go_link',
    ];

    protected $hidden = [
        'deleted_at',
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
        return route('api.v1.links.show', ['id' => $this->id]);
    }
}
