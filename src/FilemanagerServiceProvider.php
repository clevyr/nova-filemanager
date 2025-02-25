<?php

namespace Clevyr\Filemanager;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Clevyr\Filemanager\Http\Middleware\Authorize;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class FilemanagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->config();

        $this->app->booted(function () {
            $this->routes();
        });

        Nova::serving(function (ServingNova $event) {
            Nova::script('filemanager-field', __DIR__ . '/../dist/js/field.js');
            Nova::style('filemanager-field', __DIR__.'/../dist/css/tool.css');
        });
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Nova::router(['nova', Authorize::class], config('nova-filemanager.path', 'filemanager'))
            ->group(__DIR__ . '/../routes/inertia.php');

        Route::middleware(['nova', Authorize::class])
            ->namespace('Clevyr\Filemanager\Http\Controllers')
            ->prefix('nova-vendor/clevyr/nova-filemanager')
            ->group(__DIR__ . '/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    private function config()
    {
        if ($this->app->runningInConsole()) {
            // Publish config
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('filemanager.php'),
            ], 'clevyr-nova-filemanager');
        }
    }
}
