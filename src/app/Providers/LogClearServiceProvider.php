<?php

namespace Uipps\LaravelLogClear\App\Providers;

use Illuminate\Support\ServiceProvider;
use Uipps\LaravelLogClear\App\Console\Commands\LogClearCommand;

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
