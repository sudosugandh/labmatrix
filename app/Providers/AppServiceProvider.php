<?php

namespace App\Providers;

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Authenticate::redirectUsing(function (Request $request) {
            if ($request->is('doctor-portal*') && ! $request->routeIs('doctor.portal.login')) {
                return route('doctor.portal.login');
            }
            if ($request->is('corporate-portal*') && ! $request->routeIs('corporate.portal.login')) {
                return route('corporate.portal.login');
            }

            return route('login');
        });
    }
}
