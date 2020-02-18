<?php

namespace Laravel\Nova;

use Digitalcloud\MultilingualNova\NovaLanguageTool;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Actions\ActionResource;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Tools\Dashboard;
use Laravel\Nova\Tools\ResourceManager;

class NovaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->registerPublishing();
        }

        $this->registerResources();
        $this->registerTools();
        $this->registerCarbonMacros();
        $this->registerJsonVariables();

        Nova::resources([ActionResource::class]);
    }

    /**
     * Register the package's publishable resources.
     */
    protected function registerPublishing()
    {
        $this->publishes([
            __DIR__.'/Console/stubs/NovaServiceProvider.stub' => app_path('Providers/NovaServiceProvider.php'),
        ], 'nova-provider');

        $this->publishes([
            __DIR__.'/../config/nova.php' => config_path('nova.php'),
        ], 'nova-config');

        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/nova'),
        ], 'nova-assets');

        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/nova'),
        ], 'nova-lang');

        $this->publishes([
            __DIR__.'/../resources/views/partials' => resource_path('views/vendor/nova/partials'),
        ], 'nova-views');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'nova-migrations');
    }

    /**
     * Register the package resources such as routes, templates, etc.
     */
    protected function registerResources()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'nova');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'nova');
        $this->loadJsonTranslationsFrom(resource_path('lang/vendor/nova'));

        if (Nova::runsMigrations()) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }

        $this->registerRoutes();
    }

    /**
     * Register the package routes.
     */
    protected function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        });
    }

    /**
     * Get the Nova route group configuration array.
     *
     * @return array
     */
    protected function routeConfiguration()
    {
        return [
            'namespace' => 'Laravel\Nova\Http\Controllers',
            'domain' => config('nova.domain', null),
            'as' => 'nova.api.',
            'prefix' => 'nova-api',
            'middleware' => 'nova',
        ];
    }

    /**
     * Boot the standard Nova tools.
     */
    protected function registerTools()
    {
        Nova::tools([
            // new Dashboard,
            // new NovaLanguageTool(),

            new ResourceManager(),
        ]);
    }

    /**
     * Register the Nova Carbon macros.
     */
    protected function registerCarbonMacros()
    {
        Carbon::macro('firstDayOfQuarter', new Macros\FirstDayOfQuarter());
        Carbon::macro('firstDayOfPreviousQuarter', new Macros\FirstDayOfPreviousQuarter());
    }

    /**
     * Register the Nova JSON variables.
     */
    protected function registerJsonVariables()
    {
        Nova::serving(function (ServingNova $event) {
            Nova::provideToScript([
                'timezone' => config('app.timezone', 'UTC'),
                'translations' => $this->getTranslations(),
                'userTimezone' => Nova::resolveUserTimezone($event->request),
                'pagination' => config('nova.pagination', 'links'),
            ]);
        });
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        $this->commands([
            Console\ActionCommand::class,
            Console\AssetCommand::class,
            Console\BaseResourceCommand::class,
            Console\CardCommand::class,
            Console\CustomFilterCommand::class,
            Console\FilterCommand::class,
            Console\FieldCommand::class,
            Console\InstallCommand::class,
            Console\LensCommand::class,
            Console\PartitionCommand::class,
            Console\PublishCommand::class,
            Console\ResourceCommand::class,
            Console\ResourceToolCommand::class,
            Console\ThemeCommand::class,
            Console\ToolCommand::class,
            Console\TrendCommand::class,
            Console\UserCommand::class,
            Console\ValueCommand::class,
        ]);
    }

    /**
     * Get the translation keys from file.
     *
     * @return array
     */
    private static function getTranslations()
    {
        $translationFile = resource_path('lang/vendor/nova/'.app()->getLocale().'.json');

        if (!is_readable($translationFile)) {
            return [];
        }

        return json_decode(file_get_contents($translationFile), true);
    }
}
