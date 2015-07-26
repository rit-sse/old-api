<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Mentor extends Model
{
    use SoftDeletes;

    protected $appends = [
        'url'
    ];

    public function member()
    {
        return $this->belongsTo('App\Member');
    }

    public function getUrlAttribute()
    {
        return '/mentors/' . $this->id;
    }
}
