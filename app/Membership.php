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

    protected $appends = ['url'];

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

    public function getUrlAttribute()
    {
        return '/memberships/' . $this->id;
    }   
}
