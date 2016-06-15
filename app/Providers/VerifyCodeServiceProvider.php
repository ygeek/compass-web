<?php

namespace App\Providers;

use App\Services\VerifyCodeService;
use Illuminate\Support\ServiceProvider;

class VerifyCodeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('verify_code_service', function(){
           return new VerifyCodeService();
        });
    }
}
