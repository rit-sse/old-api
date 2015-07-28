<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use SoftDeletes;

    protected $appends = [
        'head_url',
        'members_url',
        'url',
    ];

    protected $hidden = [
        'deleted_at',
        'head',
        'head_id',
        'members',
    ];

    protected $dates = [
        'deleted_at',
    ];

    protected $fillable = [
        'name',
    ];

    public function head()
    {
        return $this->belongsTo('App\Officer');
    }

    public function members()
    {
        return $this->belongsToMany('App\Member')->withTimestamps();
    }

    public function getHeadUrlAttribute()
    {
        return $this->head->url;
    }

    public function getMembersUrlAttribute()
    {
        return route('api.v1.members.index', ['group' => $this->id]);
    }

    public function getUrlAttribute()
    {
        return route('api.v1.groups.show', ['id' => $this->id]);
    }
}
