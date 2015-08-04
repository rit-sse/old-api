<?php

namespace App\Providers\Auth;

class AuthProviderFactory {
    public static function create($provider, $id, $secret) {
        switch($provider) {
            case 'slack':
                return new SlackAuthProvider($id, $secret);
                break;
            case 'google':
                return new GoogleAuthProvider($id, $secret);
                break;
            default:
                return false;
        }
    }

}