<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\JwtAuth;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Get token from request header
        $token = $request->header('Authorization');

        if (!$token){
            $token = $request->header('Authorization_');
            if (!$token){
                // Return error response if token is not provided
                $data = [
                    'status' => 'error',
                    'code'   =>  403,
                    'msj'    => 'El token es invalido',
                ];
                return response()->json($data, $data['code']);
            }
        }
       
        $jwtAuth = new JwtAuth();

        $checkToken = $jwtAuth->checkTokenAdmin($token);
        // If token is valid, proceed to the next middleware or route
        if (!$checkToken) {
            // Return error response if token is invalid
            $data = [
                'status' => 'error',
                'code'   =>  403,
                'msj'    => 'El Usuario no está identificado',
            ];
            return response()->json($data, $data['code']);
        } 
        return $next($request);
    }
}
