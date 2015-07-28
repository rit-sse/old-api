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
        $provider = new GoogleRitProvider(
            $request,
            config('services.google.client_id'),
            config('services.google.client_secret'),
            config('services.google.redirect')
        );

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
            $token = JWTAuth::fromUser(
                $member, ['level' => config('auth.levels.high')]
            );
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

        $member = Member::firstOrNew([
            'email' => $user->email
        ]);

        $member->first_name = $user->user['name']['givenName'];
        $member->last_name= $user->user['name']['familyName'];

        $member->save();

        $token = JWTAuth::fromUser(
            $member, ['level' => config('auth.levels.high')]
        );

        return response()->json(['token' => $token]);
    }
}
