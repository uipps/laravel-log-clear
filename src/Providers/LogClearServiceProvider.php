<?php

namespace Uipps\LaravelLogClear\Providers;

use Illuminate\Support\ServiceProvider;
use Uipps\LaravelLogClear\Commands\LogClearCommand;

class LogClearServiceProvider extends ServiceProvider
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
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            LogClearCommand::class,
        ]);
    }
}
