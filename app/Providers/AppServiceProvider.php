<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Google\Client;

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
        if ($this->app->runningInConsole()) {
            $this->commands([
                \App\Console\Commands\GenerateGoogleCalendarToken::class,
            ]);
        }

        // Configure Google Client to disable SSL verification for development
        if (env('APP_ENV') === 'local') {
            $this->configureGoogleClientForDevelopment();
        }
    }

    private function configureGoogleClientForDevelopment(): void
    {
        // This will be applied when Google Client is instantiated
        // We'll use a wrapper in the service to handle this
    }
}

