<?php

namespace App\Providers;

use App\Models\Upload;
use App\Observers\UploadObserver;
use Illuminate\Support\ServiceProvider;

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
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Upload::observe(UploadObserver::class);
    }
}
