<?php
/**
 * Defines the Member model.
 */

namespace App;
use Log;

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

    public function can($permission, $level, $requireAll = false)
    {
        if (is_array($permission)) {
            foreach ($permission as $permName) {
                $hasPerm = $this->can($permName, $level);
                if ($hasPerm && !$requireAll) {
                    return true;
                } elseif (!$hasPerm && $requireAll) {
                    return false;
                }
            }
            // If we've made it this far and $requireAll is FALSE, then NONE of the perms were found
            // If we've made it this far and $requireAll is TRUE, then ALL of the perms were found.
            // Return the value of $requireAll;
            return $requireAll;
        } else {
            foreach ($this->roles as $role) {
                // Validate against the Permission table
                foreach ($role->perms as $perm) {
                    Log::info($perm->level);
                    if ($perm->name == $permission && $perm->level <= $level) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
}
