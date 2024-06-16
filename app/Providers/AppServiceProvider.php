<?php

namespace App\Providers;

use Barryvdh\Debugbar\Facades\Debugbar;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
        if ($this->app->isLocal()) {
            $this->app->register(IdeHelperServiceProvider::class);
        }
        $loader = AliasLoader::getInstance();
        $loader->alias('Debugbar', Debugbar::class);
    }
    
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
    
    /**
     * Bootstrap the given application.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    public function bootstrap(Application $app)
    {
        $app->configure('audit');
        $app->boot();
    }
}
