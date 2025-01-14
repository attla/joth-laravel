<?php

namespace Attla\Joth;

use Illuminate\Contracts\Http\Kernel;
use Attla\Joth\Middlewares\ModifyRequest;
use Attla\Joth\Middlewares\ModifyResponse;
use App\Http\Middleware\ModifyRequest as AppModifyRequest;
use App\Http\Middleware\ModifyResponse as AppModifyResponse;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap the application services
     *
     * @return void
     */
    public function boot()
    {
        $kernel = $this->app->make(Kernel::class);
        $kernel->pushMiddleware(
            is_file($this->app->path('Http/Middleware/ModifyRequest.php'))
            ? AppModifyRequest::class
            : ModifyRequest::class
        );
        $kernel->pushMiddleware(
            is_file($this->app->path('Http/Middleware/ModifyResponse.php'))
            ? AppModifyResponse::class
            : ModifyResponse::class
        );

        $this->publishes([
            __DIR__ . '/../stubs/Middlewares' => $this->app->path('Http/Middleware'),
        ], 'attla/joth-laravel/middlewares');

        $this->publishes([
            $this->configPath() => config_path('joth.php'),
        ], 'attla/joth-laravel/config');
    }

    /**
     * Register the application services
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            $this->configPath(),
            'joth'
        );
    }

    /**
     * Get config path
     *
     * @param bool
     */
    protected function configPath()
    {
        return __DIR__ . '/../config/joth.php';
    }
}
