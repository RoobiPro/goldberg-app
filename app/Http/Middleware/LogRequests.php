<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;

class LogRequests
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
        // Log all request headers
        foreach ($request->headers->all() as $header => $values) {
            foreach ($values as $value) {
                Log::info("Request Header: $header - $value");
            }
        }

        // Extract and log the bearer token from the request
        // $token = $request->bearerToken();

        // if ($token) {
        //     Log::info('Bearer token from request:', ['token' => $token]);

        //     // Find and log the corresponding personal access token from the database
        //     $personalAccessToken = PersonalAccessToken::findToken($token);

        //     if ($personalAccessToken) {
        //         Log::info('Database token found:', ['token' => $personalAccessToken->token]);
        //     } else {
        //         Log::warning('No matching database token found for the request token.');
        //     }
        // } else {
        //     Log::warning('No bearer token found in the request.');
        // }

        // Check and log authentication status
        // if (Auth::check()) {
        //     $user = Auth::user();
        //     Log::info('User is authenticated:', ['user_id' => $user->id, 'user_name' => $user->name]);
        // } else {
        //     Log::warning('User is not authenticated.');
        // }

        return $next($request);
    }
}
