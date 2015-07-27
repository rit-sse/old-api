<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tymon\JWTAuth\Providers\Auth\AuthInterface;

use App\Member;

class MemberAuthProvider implements AuthInterface
{
    private $member;

    public function byCredentials(array $credentials = [])
    {
        return false;
    }

    public function byId($id)
    {
        try {
            $this->member = Member::findOrFail($id);

            return true;
        } catch(ModelNotFoundException $e) {
            return false;
        }
    }

    public function user()
    {
        return $this->member;
    }
}
