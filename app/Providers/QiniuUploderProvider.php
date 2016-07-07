<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\QiniuUploder;

class QiniuUploderProvider extends ServiceProvider
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
        $this->app->bind('qiniu_uploader', function(){
            return new QiniuUploder();
        });
    }
}
