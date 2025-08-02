<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('documents', function ($user) {
            return $user->hasPermissionTo('documents');
        });
        Gate::define('documents_type', function ($user) {
            return $user->hasPermissionTo('documents_type');
        });

        Gate::define('users', function ($user) {
            return $user->hasPermissionTo('users');
        });

        Gate::define('master', function ($user) {
            return $user->hasPermissionTo('master');
        });

        Gate::define('dealers', function ($user) {
            return $user->hasPermissionTo('dealers');
        });

        Gate::define('contact_persons', function ($user) {
            return $user->hasPermissionTo('contact_persons');
        });

        Gate::define('roles', function ($user) {
            return $user->hasPermissionTo('roles');
        });

        Gate::define('colours', function ($user) {
            return $user->hasPermissionTo('colours');
        });

        Gate::define('sizes', function ($user) {
            return $user->hasPermissionTo('sizes');
        });

        Gate::define('plans', function ($user) {
            return $user->hasPermissionTo('plans');
        });

        Gate::define('brands', function ($user) {
            return $user->hasPermissionTo('brands');
        });

        Gate::define('categories', function ($user) {
            return $user->hasPermissionTo('categories');
        });

        Gate::define('product_relations', function ($user) {
            return $user->hasPermissionTo('product_relations');
        });
        Gate::define('gallery', function ($user) {
            return $user->hasPermissionTo('gallery');
        });
        Gate::define('products', function ($user) {
            return $user->hasPermissionTo('products');
        });

    }
}
