<?php
/**
 * Defines the Membership model.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Membership extends Model
{
    use SoftDeletes;

    protected $appends = [
        'member_url',
        'term_url',
        'url',
    ];

    protected $hidden = [
        'created_at',
        'deleted_at',
        'member',
        'member_id',
        'term',
        'term_id',
        'updated_at',
    ];

    protected $dates = [
        'deleted_at',
    ];

    /**
     * Establishes the One To One relationship with Member.
     */
    public function member()
    {
        return $this->belongsTo('App\Member');
    }

    /**
     * Establishes the One To One relationship with Term.
     */
    public function term()
    {
        return $this->belongsTo('App\Term');
    }

    public function getMemberUrlAttribute()
    {
        return $this->member->url;
    }

    public function getTermUrlAttribute()
    {
        return $this->term->url;
    }

    public function getUrlAttribute()
    {
        return '/memberships/' . $this->id;
    }   
}
