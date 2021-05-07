<?php

namespace bachphuc\PhpLaravelHelpers\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Blade;

class PackageServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'bachphuc\PhpLaravelHelpers\Http\Controllers';

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $packagePath = dirname(__DIR__);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /*
         * Register the service provider for the dependency.
         */

        
        $this->registerCustomDirective();
    }

    public function registerCustomDirective(){
        Blade::directive('__title', function ($expression) {
            if(!empty($expression)){
                return '<?php echo (isset($item) ? __title($item, "'. $expression .'") : ""); ?>';
            }
            return '<?php echo (isset($item) ? __title($item) : ""); ?>';
        });

        Blade::directive('__desc', function ($expression) {
            if(!empty($expression)){
                return '<?php echo (isset($item) ? __desc($item, "'. $expression .'") : ""); ?>';
            }
            return '<?php echo (isset($item) ? __desc($item) : ""); ?>';
        });

        Blade::directive('__href', function ($expression) {
            return '<?php echo (isset($item) ? __href($item) : ""); ?>';
        });

        Blade::directive('__edit_href', function ($expression) {
            return '<?php echo (isset($item) ? __edit_href($item) : ""); ?>';
        });

        Blade::directive('__id', function ($expression) {
            return '<?php echo (isset($item) ? __id($item) : ""); ?>';
        });

        Blade::directive('__g', function ($expression) {
            return '<?php echo (isset($item) ? __g($item, "'. $expression .'") : ""); ?>';
        });

        Blade::directive('__img', function ($expression) {
            if(empty($expression)){
                return '<?php echo (isset($item) ? __img($item) : ""); ?>';
            }
            return '<?php echo (isset($item) ? __img($item, '. $expression .') : ""); ?>';
        });

        Blade::directive('__owner_name', function ($expression) {
            return '<?php echo (isset($item) ? __owner_name($item) : ""); ?>';
        });

        Blade::directive('__owner_id', function ($expression) {
            return '<?php echo (isset($item) ? __owner_id($item) : ""); ?>';
        });

        Blade::directive('__owner_href', function ($expression) {
            return '<?php echo (isset($item) ? __owner_href($item) : ""); ?>';
        });

        Blade::directive('__channel_href', function ($expression) {
            return '<?php echo (isset($item) ? __channel_href($item) : ""); ?>';
        });

        Blade::directive('__created_at', function ($expression) {
            return '<?php echo (isset($item) ? __created_at($item) : ""); ?>';
        });

        Blade::directive('__duration', function ($expression) {
            return '<?php echo (isset($item) ? __duration($item) : ""); ?>';
        });
    }
}