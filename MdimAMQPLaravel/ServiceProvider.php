<?php

namespace MdimAMQPLaravel;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use MdimAMQPLaravel\Examples\HelloCommand;

class ServiceProvider extends LaravelServiceProvider implements DeferrableProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/' . Package::PREFIX . 'rabbitmq.php',
            Package::PREFIX . 'rabbitmq'
        );

        $this->app->singleton(Package::PREFIX . 'rabbitmq-publisher', function () {
            return new Publisher();
        });
        
        $this->app->singleton(Package::PREFIX . 'rabbitmq-receiver', function () {
            return new Receiver();
        });
    }
    
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/' . Package::PREFIX . 'rabbitmq.php' => config_path(Package::PREFIX . 'rabbitmq.php'),
        ], Package::PREFIX . 'rabbitmq-config');
        
        if ($this->app->runningInConsole()) {
            $this->commands([
                ReceiveCommand::class,
                HelloCommand::class,
            ]);
        }
    }
    
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Package::PREFIX . 'rabbitmq-publisher', Package::PREFIX . 'rabbitmq-receiver'];
    }
}