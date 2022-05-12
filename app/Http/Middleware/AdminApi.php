<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminApi
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            if (auth()->user()->tokenCan('server:admin')) {
                return $next($request);
            } else {
                return response()->json([
                    'message' => 'Uwaga! Brak dostępu! Obszar dostępny dla konta z uprawnieniami admina.',
                ], 403);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Zaloguj się.',
            ]);
        }
    }
}
