<?php

namespace App\Providers;

use App\Http\Resources\UserRegistrationResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->register(UserRegistrationResource::class);
        // $this->app->register(HelperResource::class);
        // $this->app->register(Database::class);
        // $this->app->bind((HelperResource::class, FirebaseResource::class);
    }
}
