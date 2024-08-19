<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRoleAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect('/')->with('error', get_label('please login', 'Please login'));
        }

        $user = Auth::user();


        if(!$user->role)
        {
            return redirect('home')->with('error', get_label('un_authorized_action', 'Un authorized action!'));
        }


        if ($user->role->rolename !== $role) {
            return redirect('home')->with('error', get_label('un_authorized_action', 'Un authorized action!'));
        }

        return $next($request);
    }
}
