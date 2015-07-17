<?php
/**
 * Defines the Term model.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'year',
    ];

    public function url()
    {
        return '/terms/' . $this->id;
    }
}
