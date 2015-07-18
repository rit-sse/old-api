<?php
/**
 * Defines the Term model.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    public $timestamps = false;

    protected $appends = ['url'];

    protected $fillable = [
        'name',
        'year',
    ];

    public function getUrlAttribute()
    {
        return '/terms/' . $this->id;
    }
}
