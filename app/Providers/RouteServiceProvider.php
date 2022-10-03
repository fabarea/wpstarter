<?php

namespace App\Providers;

use WpStarter\Cache\RateLimiting\Limit;
use WpStarter\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use WpStarter\Http\Request;
use WpStarter\Support\Facades\RateLimiter;
use WpStarter\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::namespace($this->namespace)
                ->middleware('web')
                ->group(ws_base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(5)->by(ws_optional($request->user())->id ?: $request->ip());
        });
    }
}
