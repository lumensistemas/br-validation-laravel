<?php

use Illuminate\Support\ServiceProvider;
use LumenSistemas\BrValidation\BrValidationServiceProvider;

it('registers the translation namespace', function (): void {
    $this->app->register(BrValidationServiceProvider::class);

    $translator = $this->app['translator'];

    expect($translator->hasForLocale('br-validation::validation.cnpj', 'en'))->toBeTrue()
        ->and($translator->get('br-validation::validation.cnpj', [], 'en'))->toBe('The :attribute is not a valid CNPJ.')
        ->and($translator->get('br-validation::validation.cnpj', [], 'pt_BR'))->toBe('O :attribute não é um CNPJ válido.');
});

it('registers publishable lang files', function (): void {
    $this->app->register(BrValidationServiceProvider::class);

    $publishable = ServiceProvider::pathsToPublish(BrValidationServiceProvider::class, 'br-validation-lang');

    expect($publishable)->toHaveCount(1)
        ->and(array_key_first($publishable))->toEndWith('/lang')
        ->and(array_values($publishable)[0])->toEndWith('vendor/br-validation');
});
