<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Member;

class Tip extends Model
{
    use SoftDeletes;

    protected $appends = [
        'author_url',
        'edited_by_url',
        'url',
    ];

    protected $hidden = [
        'author',
        'created_at',
        'created_by',
        'deleted_at',
        'edited_by',
        'updated_at',
        'updated_by',
    ];

    protected $dates = [
        'deleted_at',
    ];

    protected $fillable = [
        'content',
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
        return $this->edited_by ? $this->edited_by->url : null;
    }

    public function getUrlAttribute()
    {
        return '/tips/' . $this->id;
    }
}
