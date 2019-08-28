<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Container\Container;
use App\PoiDistrictService;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        Container::getInstance()->singleton(PoiDistrictService::class, function ($app) {
            return new PoiDistrictService($app);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
