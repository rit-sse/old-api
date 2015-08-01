<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use SoftDeletes;

    protected $dateFormat = \DateTime::ISO8601;

    protected $appends = [
        'agenda_item_ids',
        'agenda_items_url',
        'event_ids',
        'events_url',
        'member_ids',
        'members_url',
        'officer_url',
        'url',
    ];

    protected $hidden = [
        'agendaItems',
        'deleted_at',
        'events',
        'members',
        'officer',
    ];

    protected $dates = [
        'deleted_at',
    ];

    protected $fillable = [
        'name',
    ];

    public function agendaItems()
    {
        return $this->hasMany('App\AgendaItem');
    }

    public function events()
    {
        return $this->hasMany('App\Event');
    }

    public function members()
    {
        return $this->belongsToMany('App\Member')->withTimestamps();
    }

    public function officer()
    {
        return $this->belongsTo('App\Officer');
    }

    public function getAgendaItemIdsAttribute()
    {
        $ids = [];

        foreach($this->agendaItems as $agendaItem) {
            $ids[] = $agendaItem->id;
        }

        return $ids;
    }

    public function getAgendaItemsUrlAttribute()
    {
        return route('api.v1.agenda.index', ['group' => $this->id]);
    }

    public function getEventIdsAttribute()
    {
        $ids = [];

        foreach($this->events as $event) {
            $ids[] = $event->id;
        }

        return $ids;
    }

    public function getEventsUrlAttribute()
    {
        return route('api.v1.events.index', ['group' => $this->id]);
    }

    public function getMemberIdsAttribute()
    {
        $ids = [];

        foreach($this->members as $member) {
            $ids[] = $member->id;
        }

        return $ids;
    }

    public function getMembersUrlAttribute()
    {
        return route('api.v1.members.index', ['group' => $this->id]);
    }

    public function getOfficerUrlAttribute()
    {
        return $this->officer->url;
    }

    public function getUrlAttribute()
    {
        return route('api.v1.groups.show', ['id' => $this->id]);
    }
}
