<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use App\Models\Role;


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
        //
        Schema::defaultStringLength(191);
        view()->composer('layouts.sidebar', function (View $view) {
            $mobilizerRoles = Role::whereIn('id', [4])->get();
            $view->with('mobilizerRoles', $mobilizerRoles);
        });

    }
}
