<?php

namespace App\Http\Middleware;

use App\Enums\Core\CommKey;
use Closure;
use Illuminate\Http\Request;

class UserTfa
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request                                                                          $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->guest()) return redirect()->to("/");
        if (!env('DEMO_MODE') && user()->needsVerification() && !session(CommKey::AdminUidFromShadow->value)) return redirect()->to("/account-verification");
        return $next($request);
    }
}
