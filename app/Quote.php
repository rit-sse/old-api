<?php
/**
 * Defines the Quote model.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quote extends Model
{
    use SoftDeletes;

    protected $appends = [
        'url'
    ];

    protected $hidden = [
        'member_id',
        'created_at',
        'deleted_at',
        'updated_at',
    ];

    protected $dates = [
        'deleted_at',
    ];

    protected $fillable = [
        'approved',
        'body',
        'description',
    ];

    public function member()
    {
        return $this->belongsTo('App\Member');
    }

    public function tags()
    {
        return $this->hasMany('App\Tag');
    }

    public function getUrlAttribute()
    {
        return '/quotes/' . $this->id;
    }
}
