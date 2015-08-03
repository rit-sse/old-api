<?php

namespace App\Providers\Auth;

use App\Member;

require_once 'vendor/google/apiclient/src/Google/Client.php';
require_once 'vendor/google/apiclient/src/Google/Auth/Exception.php';

class GoogleAuthProvider implements AuthProviderInterface {

    private $dce;
    private $idToken;
    private $authLevel;
    private $client;

    public function __construct($dce, $idToken) {
        $this->idToken = $idToken;
        $this->dce = $dce;
        $this->authLevel = config('auth.levels.high');
        $this->client = new \Google_Client();
        $this->client->setClientId(config('services.google.client_id'));
        $this->client->setClientSecret(config('services.google.client_secret'));
    }

    public function verify() {
        try{
            $ticket = $this->client->verifyIdToken($this->idToken);

            if($ticket) {
                $ticket->getAttributes();
                $this->payload = $ticket->getAttributes()['payload'];
                return $this->payload['hd'] == 'g.rit.edu';
            }
        } catch(\Google_Auth_Exception $e) {
            return false;
        }
        return false;
    }

    public function authLevel() {
      return $this->authLevel;
    }

    public function findOrCreateMember(){
        $member = Member::firstOrNew([
            'dce' => explode('@', $this->payload['email'])[0]
        ]);

        $member->first_name = $this->payload['given_name'];
        $member->last_name = $this->payload['family_name'];

        return $member;
    }

}