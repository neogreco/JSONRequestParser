<?php

namespace JSON\tools;

use Illuminate\Support\ServiceProvider;

/**
 * Class PasswordServiceProvider
 */
class JsonRequestParserServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('jsonRequestParser', JsonRequestParser::class);
    }
}