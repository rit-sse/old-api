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

    protected $dateFormat = \DateTime::ISO8601;

    protected $appends = [
        'member_url',
        'term_url',
        'url',
    ];

    protected $hidden = [
        'deleted_at',
        'member',
        'member_id',
        'term',
        'term_id',
    ];

    protected $dates = [
        'deleted_at',
    ];

    protected $fillable = [
        'reason',
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
        return route('api.v1.memberships.show', ['id' => $this->id]);
    }
}
