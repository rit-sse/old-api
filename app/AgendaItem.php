<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgendaItem extends Model
{
    use SoftDeletes;

    protected $appends = [
        'author_url',
        'edited_by_url',
        'url',
    ];

    protected $dates = [
        'deleted_at',
    ];

    protected $fillable = [
        'item',
    ];

    protected $hidden = [
        'author',
        'created_by',
        'deleted_at',
        'edited_by',
        'updated_by',
    ];

    public function author()
    {
        return $this->belongsTo('App\Member', 'created_by');
    }

    public function edited_by()
    {
        return $this->belongsTo('App\Member', 'updated_by');
    }

    public function getAuthorUrlAttribute()
    {
        return $this->author->url;
    }

    public function getEditedByUrlAttribute()
    {
        return $this->edited_by ? $this->edited_by->url : '';
    }

    public function getUrlAttribute()
    {
        return route('api.v1.agenda.show', ['id' => $this->id]);
    }
}
