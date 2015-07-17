<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
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

    public function jsonSerialize()
    {
        $result = parent::jsonSerialize();
        $result['url'] = $this->url();

        return $result;
    }

    public function url()
    {
        return "/events/" . $this->id;
    }
}
