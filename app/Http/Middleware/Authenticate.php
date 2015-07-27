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
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
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
        if (!($token = $this->auth->setRequest($request)->getToken())) {
            return new JsonResponse(
                ['error' => 'authorization required'],
                Response::HTTP_UNAUTHORIZED
            );
        }

        try {
            $member = $this->auth->authenticate($token);
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

        if (!$member) {
            return new JsonResponse(
                ['error' => 'entity does not exist'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return $next($request);
    }
}
