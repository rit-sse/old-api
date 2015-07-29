<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth;

class Authenticate
{
    /**
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $auth;

    /**
     * @var array
     */
    protected $roles;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
        $this->roles = config('auth.super_roles');
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Step 1. Fail immediately if we don't have a token in the request.
        if (!($token = $this->auth->setRequest($request)->getToken())) {
            return new JsonResponse(
                ['error' => 'authorization required'],
                Response::HTTP_UNAUTHORIZED
            );
        }

        try {
            // Step 2. Validate the given token.
            $member = $this->auth->authenticate($token);

            $permissions = array_merge(
                ['level' => 1000, 'roles' => []],
                array_get(
                    config('route.permissions'),
                    $request->route()->getName(),
                    []
                )
            );

            // This ensures that super roles are not overwritten by
            // route permission configurations.
            $permissions['roles'] = array_merge(
                $permissions['roles'],
                $this->roles
            );

            $level = $permissions['level'];

            // Step 3. Check the auth level encoded in the token.
            if ($this->auth->getPayload()->get('level') < $level) {
                return new JsonResponse(
                    ['error' => 'authentication level not high enough'],
                    Response::HTTP_FORBIDDEN
                );
            }

            // Step 4. Verify the role(s) of the member.
            $roles = $permissions['roles'];
            if (!($member->hasRole($roles))) {
                return new JsonResponse(
                    ['error' => 'invalid permissions'],
                    Response::HTTP_FORBIDDEN
                );
            }

            // Step 5. Attach member to the current request.
            $request->member = $member;
        } catch (TokenExpiredException $e) {
            return new JsonResponse(
                ['error' => 'token has expired'],
                Response::HTTP_FORBIDDEN
            );
        } catch (TokenInvalidException $e) {
            return new JsonResponse(
                ['error' => 'token is invalid'],
                Response::HTTP_FORBIDDEN
            );
        } catch (JWTException $e) {
            return new JsonResponse(
                ['error' => 'unknown error'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        // Step 6. ???
        if (!$member) {
            return new JsonResponse(
                ['error' => 'entity does not exist'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        // Step 7. Profit!
        return $next($request);
    }
}
