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
    public function getToken(Request $request, $providerName)
    {
        $this->validate($request, [
            'id' => 'required',
            'secret' => 'required',
        ]);

        $body = $request->only(['id', 'secret']);

        $id = $body['id'];
        $secret = $body['secret'];

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
}
