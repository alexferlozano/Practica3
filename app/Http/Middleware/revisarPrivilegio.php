<?php

namespace App\Http\Middleware;

use Closure;

class revisarPrivilegio
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
        if ($request->rol != null) {
            return redirect('registro');
        }
        return $next($request);
    }
}
