<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

use Illuminate\Support\Facades\DB;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            \Log::info('User not authenticated, redirecting to login');
            return route('login');
        }
    }

    public function handle($request, Closure $next, ...$guards)
    {

        if ($request->ajax()) {
            return $next($request);
        }
        $this->authenticate($request, $guards);
        
        // Set a flag in the session to indicate authentication state
        $request->session()->put('isAuthenticated', true);

        $user_role_id = Auth::user()->role_id;

        $status = Auth::user()->status;

        if ($status == 0) {
            abort(403, 'You Are blocked by the Admin.');
        }

        $permission_ids = DB::table('role_permissions')->where('role_id', $user_role_id)->pluck('permission_id');

        $allowedRoutes = DB::table('permissions')->whereIn('id', $permission_ids)->pluck('route');

        $currentRouteName = $request->route()->getName();
        // echo $currentRouteName; exit;

        $isAllowed = $allowedRoutes->contains($currentRouteName) || ($currentRouteName == 'dashboard' || ($currentRouteName == 'logout.get') || ($currentRouteName == 'logout'));
        // echo $isAllowed; exit;

        $isDeveloper = env('DEVELOPER');
        // echo $isDeveloper; exit;


        // print_r($isAllowed); exit;

        if ($isAllowed || ($isDeveloper == true)) {            
            return $next($request);
        }
        else{
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);

    }
}
