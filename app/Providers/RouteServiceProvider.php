<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    protected $public_namespace = "App\Http\Controllers";
    protected $admin_namespace = "App\Http\Controllers\Admin";
    protected $launcher_namespace = "App\Http\Controllers\Launcher";
    //protected $textures_namespace = "App\Http\Controllers\Textures";
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::namespace($this->public_namespace)->group(base_path('routes/api/public.php'));
            //Route::namespace($this->public_namespace)->prefix("textures")->group(base_path('routes/api/textures.php'));
            Route::namespace($this->admin_namespace)->prefix("admin")->group(base_path('routes/api/admin.php'));
            Route::namespace($this->launcher_namespace)->prefix("launcher")->group(base_path('routes/launcher.php'));
            Route::namespace($this->admin_namespace)->prefix("plugins")->group(base_path('routes/plugins.php'));
        });
    }
}
