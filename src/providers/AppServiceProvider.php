<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/13/20, 6:07 PM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\iProduct\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if($this->app->runningInConsole())
        {
            if (iproduct('database.migrations.include', true)) $this->loadMigrationsFrom(iproduct_path('database/migrations'));
        }
    }

    public function register()
    {
        parent::register();
        $this->mergeConfigFrom(iproduct_path('config/iproduct.php'), 'ilaravel.iproduct');
    }
}
