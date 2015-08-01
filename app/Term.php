<?php
/**
 * Defines the Term model.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    public $timestamps = false;

    protected $dateFormat = \DateTime::ISO8601;

    protected $appends = [
        'name',
        'url',
    ];

    protected $fillable = [
        'start_date',
        'end_date',
    ];

    public function getNameAttribute()
    {
        $date = date_create($this->start_date);
        $month = $date->format('m');
        $name = (8 <= $month && $month <= 12) ? 'Fall' : 'Spring';

        return $name . ' ' . $date->format('Y');
    }

    public function getUrlAttribute()
    {
        return route('api.v1.terms.show', ['id' => $this->id]);
    }
}
