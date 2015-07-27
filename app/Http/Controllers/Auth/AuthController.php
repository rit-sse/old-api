<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use JWTAuth;

use App\Member;
use App\Providers\GoogleRitProvider;

class AuthController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProvider(Request $request)
    {
        // FIXME: Use GoogleRitProvider to make the request.
        // The main different that GoogleRitProvider brings is
        // that it adds an 'hd' parameter to the request that
        // restricts which domains our app will accept. For some
        // reason, the GoogleRitProvider doesn't generate a state
        // string, which causes problems for the callback.
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
        if (\App::environment('local')) {
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
        if (!ends_with(array_get($user->user, 'domain', ''), 'rit.edu')) {
            return new JsonResponse(
                ['error' => 'domain user not authorized'],
                Response::HTTP_FORBIDDEN
            );
        }

        // FIXME: Probably don't want firstOrCreate here, in case
        // someone changes their name on Google (e.g. JOHN -> John).
        $member = Member::firstOrCreate([
            'first_name' => $user->user['name']['givenName'],
            'last_name' => $user->user['name']['familyName'],
            'email' => $user->email
        ]);

        $token = JWTAuth::fromUser($member);
        return response()->json(['token' => $token]);
    }
}
