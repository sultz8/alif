<?php

namespace App\Providers;

use App\Models\PersonalAccessToken;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        if ($this->app->runningUnitTests()) {
            Schema::defaultStringLength(191);
        }

        Password::defaults(function () {
            return Password::min(8);
        });
    }
}
