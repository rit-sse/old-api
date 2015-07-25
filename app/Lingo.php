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

    protected $appends = [
        'url',
    ];

    protected $table = 'lingo';

    protected $hidden = [
        'created_at',
        'deleted_at',
        'updated_at',
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
        return '/lingo/' . $this->id;
    }
}
