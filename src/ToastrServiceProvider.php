<?php

namespace Bitgroupasia\Toastr;

use Bitgroupasia\Toastr\Toastr;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application as LaravelApplication;

class ToastrServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('toastr', function (Container $app) {
            return new Toastr($app['session']);
        });
        $this->app->alias('toastr', Toastr::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerBladeDirectives();
    }

    /**
     * Register Blade Directives
     */
    public function registerBladeDirectives()
    {
        Blade::directive('toastr_render', function () {
            return "<?php echo app('toastr')->render(); ?>";
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'toastr',
        ];
    }
}
