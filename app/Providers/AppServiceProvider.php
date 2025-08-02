<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $user_role_id = Auth::user()->role_id;
                $permission_ids = DB::table('role_permissions')
                    ->where('role_id', $user_role_id)
                    ->pluck('permission_id');

                $allowedSlugs = DB::table('permissions')
                    ->whereIn('id', $permission_ids)
                    ->pluck('slug')
                    ->toArray();

                $view->with('allowedSlugs', $allowedSlugs);
            }
        });
    }
}
