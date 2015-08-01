<?php
/**
 * Defines the Officer model.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Officer extends Model
{
    use SoftDeletes;

    protected $dateFormat = \DateTime::ISO8601;

    protected $appends = [
        'email',
        'member_url',
        'position',
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
        'title',
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

    public function getEmailAttribute()
    {
        return $this->position . '@' . config('app.email_domain');
    }

    public function getMemberUrlAttribute()
    {
        return $this->member->url;
    }

    public function getPositionAttribute()
    {
        return str_replace(' ', '_', strtolower($this->title));
    }

    public function getTermUrlAttribute()
    {
        return $this->term->url;
    }

    public function getUrlAttribute()
    {
        return route('api.v1.officers.show', ['id' => $this->id]);
    }
}
