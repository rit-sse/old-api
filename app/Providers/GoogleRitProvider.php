<?php

namespace App\Providers;

use Laravel\Socialite\Two\GoogleProvider;

class GoogleRitProvider extends GoogleProvider
{
    protected function getCodeFields($state = null)
    {
        $fields = parent::getCodeFields($state);

        $fields['hd'] = 'g.rit.edu';

        return $fields;
    }
}
