<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $dateFormat = \DateTime::ISO8601;

    /**
     * @var array
     */
    protected $appends = [
        'creator_url',
        'assignee_url',
        'url',
    ];

    /**
     * @var array hidden attributes
     */
    protected $hidden = [
        'creator',
        'assignee'
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
        'name',
        'description',
        'completed'
    ];

    protected $casts = [
        'completed' => 'boolean' // Always cast Task->completed as bool
    ];

    /**
     * Relationship Task->creator
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo('App\Member');
    }

    /**
     * Relationship Task->assignee
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assignee()
    {
        return $this->belongsTo('App\Member');
    }

    /**
     * @return string route to creator->url
     */
    public function getCreatorUrlAttribute()
    {
        return $this->creator->url;
    }

    /**
     * @return string route to assignee->url
     */
    public function getAssigneeUrlAttribute()
    {
        return $this->assignee->url;
    }

    /**
     * IETF url getter.
     */
    public function getUrlAttribute()
    {
        return route('api.v1.tasks.show', ['id' => $this->id]);
    }
}
