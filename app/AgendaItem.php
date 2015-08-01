<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgendaItem extends Model
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
        'body',
    ];

    protected $hidden = [
        'group',
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
        return route('api.v1.agenda.show', ['id' => $this->id]);
    }
}
