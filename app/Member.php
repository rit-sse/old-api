<?php
/**
 * Defines the Member model.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class Member extends Model
{
    use EntrustUserTrait;
    use SoftDeletes;

    protected $dateFormat = \DateTime::ISO8601;

    protected $appends = [
        'memberships_url',
        'url'
    ];

    protected $hidden = [
        'deleted_at',
        'memberships',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'dce',
    ];

    /**
     * Establishes the inverse One To Many relationship with Group.
     */
    public function groups()
    {
        return $this->belongsToMany('App\Group')->withTimestamps();
    }

    /**
     * Establishes the One To Many relationship with Membership.
     */
    public function memberships()
    {
        return $this->hasMany('App\Membership');
    }

    /**
     * Establishes the One To One relationship with Mentor.
     */
    public function mentor()
    {
        return $this->hasOne('App\Mentor');
    }

    /**
     * Establishes the One To One relationship with Officer.
     */
    public function officer()
    {
        return $this->hasOne('App\Officer');
    }

    /**
     *
     */
    public function getMembershipsUrlAttribute()
    {
        return route('api.v1.memberships.index', ['member' => $this->id]);
    }

    /**
     * IETF url getter.
     */
    public function getUrlAttribute()
    {
        return route('api.v1.members.show', ['id' => $this->id]);
    }
}
