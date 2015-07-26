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
        'url'
    ];

    protected $hidden = [
        'created_at',
        'deleted_at',
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
     * IETF url getter.
     */
    public function getUrlAttribute()
    {
        return '/members/' . $this->id;
    }
}
