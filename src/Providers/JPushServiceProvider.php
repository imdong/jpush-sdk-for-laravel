<?php

namespace ImDong\JPush\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * JPushServiceProvider
 *
 * @package ImDong\JPush\Providers
 *
 * @author  ImDong (www@qs5.org)
 * @created 2021-03-20 13:40
 */
class JPushServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $path = realpath(__DIR__ . '/../../configs/laravel_jpush.php');

        $this->publishes([
            $path => config_path('jpush.php'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $path = realpath(__DIR__ . '/../../configs/laravel_jpush.php');

        // 将给定配置文件合现配置文件接合
        $this->mergeConfigFrom($path, 'jpush');

        // bind
        $this->app->singleton('JPush', function () {
            $config = config('services.jpush', config('jpush'));

            return new \JPush\Client($config['app_key'], $config['master_secret'], $config['log_file']);
        });
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
