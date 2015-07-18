<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Committee extends Model
{
    use SoftDeletes;

    protected $appends = ['members_url', 'url'];

    protected $hidden = [
        'created_at',
        'deleted_at',
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

    public function getMembersUrlAttribute()
    {
        return $this->getUrlAttribute() . '/members';
    }

    public function getUrlAttribute()
    {
        return '/committees/' . $this->id;
    }
}
