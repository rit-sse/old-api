<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use SoftDeletes;

    protected $appends = ['head_url', 'url'];

    protected $hidden = [
        'created_at',
        'deleted_at',
        'head',
        'updated_at',
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

    public function getUrlAttribute()
    {
        return '/groups/' . $this->id;
    }
}
