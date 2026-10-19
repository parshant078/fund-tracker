<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
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
        if ((bool) config('app.force_root_url', false)) {
            URL::forceRootUrl((string) config('app.url'));
            if (parse_url((string) config('app.url'), PHP_URL_SCHEME) === 'https') {
                URL::forceScheme('https');
            }
        }
    }
}
