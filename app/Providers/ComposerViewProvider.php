<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\ParameterLocaleRepository;

use View;

class ComposerViewProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */

    public function register()
    {
        //
        View::composer('*', function($view) {
            $locale_list = [];
            $view->with('locales', $locale_list);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
