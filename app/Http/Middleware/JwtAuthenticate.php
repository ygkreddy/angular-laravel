<?php

namespace Camp\Http\Middleware;

use Closure;
use Tymon\JWTAuth\JWTAuth;

class JwtAuthenticate
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
        if (!\JWTAuth::getToken())
        {
            return response()->json(['status' => 401, 'error' => 'token_absent'], 200);
        } 
        else
        {
            try
            {
                $authenticate = \JWTAuth::parseToken()->authenticate();
                if (!$authenticate)
                {
                    return response()->json(['status' => 401, 'error' => 'user_not_found'], 200);
                }
            } 
            catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e)
            {
                return response()->json(['status' => 401, 'error' => 'token_expired'], 200);
            } 
            catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e)
            {
                return response()->json(['status' => 401, 'error' => 'token_invalid'], 200);
            }
        }

        return $next($request);
    }

}
