<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\Member;
use JWTAuth;

class AuthController extends Controller
{
    private $pattern = '/(.*)@(g.)?rit.edu/';

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProvider(Request $request)
    {
        $provider = \Socialite::driver('google');
        $provider->scopes(['email', 'profile']);

        return $provider->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback(Request $request)
    {
        if (env('APP_ENV', 'local')) {
            $member = Member::findOrFail(1);
            $token = JWTAuth::fromUser($member);
            return response()->json($token);
        }

        $provider = new GoogleRitProvider(
            $request,
            config('services.google.client_id'),
            config('services.google.client_secret'),
            config('services.google.redirect')
        );

        $user = $provider->user();
        if (array_get($user->user, 'domain', '') != 'g.rit.edu') {
            return new JsonResponse(
                ['error' => 'Domain user not authorized.'],
                Response::HTTP_FORBIDDEN
            );
        }

        if (preg_match($this->pattern, $user->email, $matches)) {
            $username = $matches[1];
            $member = Member::firstOrCreate([
                'first_name' => $user->user['name']['givenName'],
                'last_name' => $user->user['name']['familyName'],
                'username' => $username
            ]);

            $token = JWTAuth::fromUser($member);
            return response()->json($token);
        } else {
            abort(500);
        }
    }
}
