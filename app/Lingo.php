<?php
/**
 * Defines the Lingo model.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lingo extends Model
{
    use SoftDeletes;

    protected $dateFormat = \DateTime::ISO8601;

    protected $appends = [
        'url'
    ];

    protected $table = 'lingo';

    protected $hidden = [
        'deleted_at',
    ];

    protected $dates = [
        'deleted_at',
    ];

    protected $fillable = [
        'phrase',
        'definition',
    ];

    public function getUrlAttribute()
    {
        return route('api.v1.lingo.show', ['id' => $this->id]);
    }
}
