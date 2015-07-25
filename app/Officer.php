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

    protected $appends = [
        'email',
        'member_url',
        'position',
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

    public function getPositionAttribute()
    {
        return str_replace(' ', '_', strtolower($this->title));
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
        return '/officers/' . $this->id;
    }
}
