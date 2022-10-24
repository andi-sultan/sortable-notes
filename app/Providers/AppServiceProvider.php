<?php

namespace App\Providers;

use App\Models\Label;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
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
        // Show User Labels in sidebar menu
        View::composer('*', function () {
            if (Auth::check()) {
                $label = Label::where('user_id', '=',  Auth::user()->id)->get(['id', 'name'])->toArray();
                View::share('menuLabels', $label);
            }
        });
    }
}
