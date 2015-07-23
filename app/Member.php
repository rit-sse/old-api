<?php
/**
 * Defines the Member model.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use SoftDeletes;

    protected $appends = [
        'groups_url',
        'memberships_url',
        'url',
    ];

    protected $hidden = [
        'created_at',
        'deleted_at',
        'groups',
        'memberships',
        'updated_at',
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
        'username',
    ];

    /**
     * Establishes the One To Many relationship with Group.
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
     * IETF url getter.
     */
    public function getGroupsUrlAttribute()
    {
        return '/groups?member=' . $this->id;
    }   

    /**
     * IETF url getter.
     */
    public function getMembershipsUrlAttribute()
    {
        return '/memberships?member=' . $this->id;
    }   

    /**
     * IETF url getter.
     */
    public function getUrlAttribute()
    {
        return '/members/' . $this->id;
    }   
}
