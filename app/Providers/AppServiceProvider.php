<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        URL::forceScheme('https');

        View::composer('*', function ($view) {
            $footerYear = optional(Setting::find('footer_year'))->value ?? date('Y');
            $view->with('footerYear', $footerYear);
        });
    }
}
