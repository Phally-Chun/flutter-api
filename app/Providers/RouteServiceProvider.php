<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;


class RouteServiceProvider extends ServiceProvider
{
    protected $apiWebsiteNamespaceV1 = 'App\Http\Controllers\Api\V1\App';
    protected $web = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        Route::middleware('web')
            ->namespace($this->web)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

    public function map()
    {
        $this->mapApiV1Route();
    }

    protected function mapApiV1Route()
    {
        // Route::prefix('api/v1/app')
        //     ->namespace($this->apiAdminNamespaceV1)
        //     ->group(base_path('routes/api/v1/admin.php'));

        Route::prefix('api/v1/app')
            ->namespace($this->apiWebsiteNamespaceV1)
            ->group(base_path('routes/api/v1/app.php'));
    }
}
