<?php
/**
 * Defines the Officer model.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Officer extends Model
{
    use SoftDeletes;

    protected $hidden = [
        'created_at',
        'deleted_at',
        'updated_at',
    ];

    protected $dates = [
        'deleted_at',
    ];

    protected $fillable = [
        'position',
    ];

    /**
     * Establishes the One To One relationship with Member.
     */
    public function member()
    {
        return $this->belongsTo('App\Member');
    }

    public function url()
    {
        return '/officers/' . $this->id;
    }
}
