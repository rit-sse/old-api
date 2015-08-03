<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class Authenticate extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $token = $this->auth->setRequest($request)->getToken()) {
            return $this->respond('tymon.jwt.absent', 'token_not_provided', 400);
        }
        try {
            $member = $this->auth->authenticate($token);
        } catch (TokenExpiredException $e) {
            return $this->respond('tymon.jwt.expired', 'token_expired', $e->getStatusCode(), [$e]);
        } catch (JWTException $e) {
            return $this->respond('tymon.jwt.invalid', 'token_invalid', $e->getStatusCode(), [$e]);
        }
        if (! $member) {
            return $this->respond('tymon.jwt.user_not_found', 'user_not_found', 404);
        }
        $level = $this->auth->getPayload()->get('level');
        if(!$member->can($request->route()->getName(), $level)) {
            return new JsonResponse(
                ['error' => 'invalid permissions' ],
                Response::HTTP_FORBIDDEN
            );
        }
        $request->member = $member;
        return $next($request);
    }
}