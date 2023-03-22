<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Sentry\State\Scope;
use function Sentry\configureScope;

class SentryUser
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(auth()->check() && app()->bound('sentry'))
        {
            configureScope(function (Scope $scope): void {
                $scope->setUser([
                    'id' => auth()->id(),
                    'email' => auth()->user()->email,
                ]);
            });
        }

        return $next($request);
    }
}
