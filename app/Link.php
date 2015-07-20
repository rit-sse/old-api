<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Link extends Model
{
    use SoftDeletes;

    protected $dates = [
        'deleted_at',
    ];

    protected $fillable = [
        'expanded_link',
        'go_link',
    ];

    protected $hidden = [
        'created_at',
        'deleted_at',
        'updated_at',
    ];

    public function creator()
    {
        return $this->belongsTo('App\Member');
    }

    public function getUrlAttribute()
    {
        return '/links/' . $this->id;
    }
}
