<?php

namespace App\Providers\Auth;

use App\Member;

class SlackAuthProvider implements AuthProviderInterface {

        private $dce;
        private $secret;
        private $authLevel;

        public function __construct($dce, $secret) {
            $this->secret = $secret;
            $this->dce = $dce;
            $this->authLevel = config('auth.levels.low');
        }

        public function verify() {
            $validSecret = in_array($this->secret, config('auth.secrets'));
            $validDCE = preg_match("/[a-z]{2,3}\d{4}/", $this->dce);
            return $validSecret && $validDCE;
        }

        public function authLevel() {
            return $this->authLevel;
        }

        public function findOrCreateMember() {
            $member = Member::firstOrNew([
                'dce' => $this->dce
            ]);

            if(!$member->first_name){
                $member->first_name = '';
            }

            if(!$member->last_name) {
                $member->last_name = '';
            }

            return $member;
        }
}