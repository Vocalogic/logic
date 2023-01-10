<?php

namespace App\Http\Middleware;

use App\Enums\Core\ACL;
use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->guest()) abort(401);
        if (user()->account_id != 1) abort(401);
        if(!isAdmin()) return redirect()->to("sales/");
        return $next($request);
    }
}
