<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use ScreenshotMachine\ScreenshotMachine;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->app->bind(ScreenshotMachine::class, function () {
            return new ScreenshotMachine(
                config('services.screenshotmachine.key'),
                config('services.screenshotmachine.secret')
            );
        });
    }
}
