<?php

namespace Modules\OAuth\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Facades\Auth;

define('SAMPLE_OAUTH', 'oauth');

class OAuthServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->hooks();
    }

    /**
     * Module hooks.
     */
    public function hooks()
    {
        \Eventy::addFilter('settings.sections', function($sections) {
            $sections['oauth'] = ['title' => __('OAuth'), 'icon' => 'user', 'order' => 700];

            return $sections;
        }, 30);

        // Section settings
        \Eventy::addFilter('settings.section_settings', function($settings, $section) {

            if ($section != 'oauth') {
                return $settings;
            }

            $settings = \Option::getOptions([
                'oauth.active',
                'oauth.client_id',
                'oauth.client_secret',
                'oauth.auth_url',
                'oauth.token_url',
                'oauth.user_url',
            ]);

            return $settings;
        }, 20, 2);

        // Section parameters.
        \Eventy::addFilter('settings.section_params', function($params, $section) {
            if ($section != 'oauth') {
                return $params;
            }

            $params = [
                'template_vars' => [],
                'validator_rules' => [],
            ];

            return $params;
        }, 20, 2);

        // Settings view name
        \Eventy::addFilter('settings.view', function($view, $section) {
            if ($section != 'oauth') {
                return $view;
            } else {
                return 'oauth::index';
            }
        }, 20, 2);

        \Eventy::addFilter('middleware.web.custom_handle.response', function($prev, $rq, $next) {
            $path = $rq->path();
            $loggedIn = Auth::check();

            $settings = \Option::getOptions([
                'oauth.active',
                'oauth.client_id',
                'oauth.auth_url',
            ]);

            if (!$rq->get('disable_oauth', false) && $path == 'login' && !$loggedIn &&
                $settings['oauth.active'] == 'on') {

                $con = '?';
                if (strpos($settings['oauth.auth_url'], '?') !== false) {
                    $con = '&';
                }

                return redirect(
                    sprintf("%s%sclient_id=%s&response_type=code&redirect_uri=%s",
                        $settings['oauth.auth_url'],
                        $con,
                        $settings['oauth.client_id'],
                        route('oauth_callback')
                    )
                );
            }

            return $prev;
        }, 10, 3);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerTranslations();
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('oauth.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'oauth'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/oauth');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/oauth';
        }, \Config::get('view.paths')), [$sourcePath]), 'oauth');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $this->loadJsonTranslationsFrom(__DIR__ .'/../Resources/lang');
    }

    /**
     * Register an additional directory of factories.
     * @source https://github.com/sebastiaanluca/laravel-resource-flow/blob/develop/src/Modules/ModuleServiceProvider.php#L66
     */
    public function registerFactories()
    {
        if (! app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/../Database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
