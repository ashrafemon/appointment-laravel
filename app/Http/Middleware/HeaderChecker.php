<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HeaderChecker
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $headerError = [];
        $acceptHeader = array('Accept' => "application/json");
        $contentHeader = array('Content-Type' => "application/json or multipart/form-data");

        if (request()->isMethod('get')) {
            if (request()->header('Accept') === 'application/json') {
                return $next($request);
            } else {
                array_push($headerError, $acceptHeader);

                return response([
                    'message' => 'Please use required headers',
                    'headers' => $headerError
                ])->setStatusCode(200);
            }
        } else {
            if (
                (request()->header('Accept') === 'application/json') &&
                (request()->hasHeader('Content-Type'))
            ) {
                return $next($request);
            } else {
                array_push($headerError, $acceptHeader, $contentHeader);

                return response([
                    'message' => 'Please use required headers',
                    'headers' => $headerError
                ])->setStatusCode(200);
            }
        }
    }
}
