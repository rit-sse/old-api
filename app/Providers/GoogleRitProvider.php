<?php

namespace App\Providers;

use Laravel\Socialite\Two\GoogleProvider;
use Illuminate\Http\Request;

class GoogleRitProvider extends GoogleProvider
{
    protected $callback;

    public function __construct(Request $request, $callback = null)
    {
        parent::__construct(
            $request,
            config('services.google.client_id'),
            config('services.google.client_secret'),
            config('services.google.redirect')
        );

        $this->callback = $callback;
    }

    public function getCallback()
    {
        $decodedState = base64_decode($this->request->input('state'));
        $state = unserialize($decodedState);

        $callback = array_get($state, 'callback', null);

        return $callback;
    }

    protected function getCodeFields($random = null)
    {
        if ($this->callback) {
            $state = [
                'callback' => $this->callback,
                'random' => $random,
            ];
        } else {
            $state = [
                'random' => $random,
            ];
        }

        $encodedState = base64_encode(serialize($state));
        $fields = parent::getCodeFields($encodedState);
        $fields['hd'] = 'g.rit.edu';

        return $fields;
    }

    protected function hasInvalidState()
    {
        if ($this->isStateless()) {
            return false;
        }

        $session = $this->request->getSession();
        $randomData = array_get(unserialize(
            base64_decode($this->request->input('state'))
        ), 'random', '');

        return ! ($randomData === $session->get('state'));
    }
}
