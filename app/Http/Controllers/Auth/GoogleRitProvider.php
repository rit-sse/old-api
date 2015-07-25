<?php

namespace App\Http\Controllers\Auth;

use Laravel\Socialite\Two\GoogleProvider;

class GoogleRitProvider extends GoogleProvider
{
    protected function getCodeFields($states = null)
    {
        $fields = parent::getCodeFields();

        $fields['hd'] = 'g.rit.edu';

        return $fields;
    }
}
