<?php

namespace App\Http\Middleware;

use Closure;

class rol
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
        if ($request->rol == 'admin' || $request->rol == 'user') {
            return $next($request);
        }
        return redirect('usuarios');
    }
}
