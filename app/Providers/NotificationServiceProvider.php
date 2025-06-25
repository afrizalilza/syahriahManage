<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Using a closure based composer...
        view()->composer('*', function ($view) {
            if (auth()->check()) {
                $unreadNotifications = auth()->user()->unreadNotifications()->latest()->take(5)->get();
                $view->with('unreadNotifications', $unreadNotifications);
            }
        });
    }
}
