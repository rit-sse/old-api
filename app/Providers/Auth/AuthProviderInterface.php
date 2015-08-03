<?php

namespace App\Providers\Auth;

interface AuthProvider {

    public function verify();
    public function authLevel();
    public function findOrCreateMember();

}