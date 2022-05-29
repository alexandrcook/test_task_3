<?php namespace App\Providers;

use App\Routing\ExtendedRouter;
use Illuminate\Support\ServiceProvider;

class ExtendedRouteServiceProvider extends ServiceProvider {

    protected $defer = true;

    public function register()
    {
        $this->app['router'] = $this->app->share(function() { return new ExtendedRouter($this->app); });
    }

    public function provides()
    {
        return array('router');
    }

}
