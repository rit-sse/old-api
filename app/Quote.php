<?php
/**
 * Defines the Quote model.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quote extends Model
{
    use SoftDeletes;

    protected $hidden = [
        'member_id',
        'created_at',
        'deleted_at',
        'updated_at',
    ];

    protected $dates = [
        'deleted_at',
    ];

    protected $fillable = [
        'content',
    ];

    public function member()
    {
        return $this->belongsTo('App\Member');
    }

    public function url()
    {
        return '/quotes/' . $this->id;
    }
}
