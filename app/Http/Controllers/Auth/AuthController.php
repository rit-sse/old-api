<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JWTAuth;

use App\Http\Controllers\Controller;
use App\Member;
use App\Providers\Auth\AuthProviderFactory;
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
        $providerName = $queryParameters['provider'];
        $secret = $queryParameters['secret'];

        $provider = AuthProviderFactory::create($providerName, $id, $secret);

        if(!$provider) {
            return new JsonResponse(
                ['error' => 'invalid provider'], Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }


        if(!$provider->verify()) {
            return new JsonResponse(
                ['error' => 'invalid id or secret'], Response::HTTP_UNAUTHORIZED
            );
        }

        $member = $provider->findOrCreateMember();
        $member->save();
        if (!($member->hasRole('member'))) {
            $member->attachRole(
                Role::where('name', 'member')->firstOrFail()
            );
        }

        $token = JWTAuth::fromUser(
            $member,
            [
                'level' => $provider->authLevel(),
                'member' => $member
            ]
        );

        return response()->json(['token' => $token]);
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
