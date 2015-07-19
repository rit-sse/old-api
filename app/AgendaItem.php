<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgendaItem extends Model
{
    use SoftDeletes;

    protected $appends = [
        'url',
    ];

    protected $dates = [
        'deleted_at',
    ];

    protected $fillable = [
        'item',
    ];

    protected $hidden = [
        'created_at',
        'deleted_at',
        'updated_at',
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

    public function getUrlAttribute()
    {
        return '/agenda/' . $this->id;
    }
}
