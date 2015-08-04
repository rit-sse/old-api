<?php

namespace App\Providers\Auth;

interface AuthProviderInterface {

    public function verify();
    public function authLevel();
    public function findOrCreateMember();

}