<?php

declare(strict_types=1);

namespace LumenSistemas\BrValidation;

use Illuminate\Support\ServiceProvider;

final class BrValidationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'br-validation');

        $this->publishes([
            __DIR__.'/../lang' => $this->app->langPath('vendor/br-validation'),
        ], 'br-validation-lang');
    }
}
