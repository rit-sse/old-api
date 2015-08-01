<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JWTAuth;

use App\Http\Controllers\Controller;
use App\Member;
use App\Providers\GoogleRitProvider;
use App\Role;

class AuthController extends Controller
{
    public function getToken(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'provider' => 'required',
            'secret' => 'required',
        ]);

        $queryParameters = $request->only(['id', 'provider', 'secret']);

        $id = $queryParameters['id'];
        $provider = $queryParameters['provider'];
        $secret = $queryParameters['secret'];

        if (!(in_array($secret, config('auth.secrets')))) {
            return new JsonResponse(
                ['error' => 'invalid secret'], Response::HTTP_FORBIDDEN
            );
        }

        $member = Member::whereHas(
            'externalProfiles', function ($query) use ($id, $provider) {
                $query->where('identifier', $id);
                $query->where('provider', $provider);
            }
        );

        try {
            $member = $member->firstOrFail();

            $token = JWTAuth::fromUser(
                $member,
                [
                    'level' => config('auth.levels.low'),
                    'member' => $member,
                ]
            );

            return response()->json(['token' => $token]);
        } catch (ModelNotFoundException $e) {
            return new JsonResponse(
                ['error' => 'not found'], Response::HTTP_NOT_FOUND
            );
        }
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProvider(Request $request)
    {
        $callback = $request->input('callback');

        $provider = new GoogleRitProvider(
            $request,
            $callback
        );

        $provider->scopes(
            ['email', 'profile']
        );

        return $provider->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback(Request $request)
    {
        // Used for development purposes. Hit /auth/google/callback
        // to get a dummy JWT for local use.
        if (\App::environment('local')) {
            $member = Member::findOrFail(1);

            if (!($member->hasRole('member'))) {
                $member->attachRole(
                    Role::where('name', 'member')->firstOrFail()
                );
            }

            $token = JWTAuth::fromUser(
                $member,
                [
                    'level' => config('auth.levels.high'),
                    'member' => $member,
                ]
            );

            return response()->json($token);
        }

        $provider = new GoogleRitProvider(
            $request
        );

        $user = $provider->user();
        if (array_get($user->user, 'domain', '') != 'g.rit.edu') {
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

        if (!($member->hasRole('member'))) {
            $member->attachRole(Role::where('name', 'member')->firstOrFail());
        }

        $token = JWTAuth::fromUser(
            $member,
            [
                'level' => config('auth.levels.high'),
                'member' => $member,
            ]
        );

        if ($callback = $provider->getCallback()) {
            return redirect($callback . '?token=' . $token);
        } else {
            return response()->json(['token' => $token]);
        }
    }
}
