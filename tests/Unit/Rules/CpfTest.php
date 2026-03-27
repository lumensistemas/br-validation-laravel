<?php

declare(strict_types=1);

use LumenSistemas\BrValidation\Rules\Cpf;

it('accepts valid cpfs', function (string $cpf): void {
    $rule = new Cpf();
    $failed = false;

    $rule->validate('cpf', $cpf, failClosure($failed));

    expect($failed)->toBeFalse();
})->with([
    'masked' => ['856.981.040-77'],
    'masked with zero check digit' => ['630.855.000-06'],
    'another masked' => ['299.635.220-33'],
    'unmasked' => ['19357075070'],
    'another unmasked' => ['84250974014'],
]);

it('rejects invalid cpfs', function (mixed $cpf): void {
    $rule = new Cpf();
    $failed = false;

    $rule->validate('cpf', $cpf, failClosure($failed));

    expect($failed)->toBeTrue();
})->with([
    'all zeros' => ['00000000000'],
    'all ones' => ['11111111111'],
    'all nines' => ['99999999999'],
    'too short' => ['1234567890'],
    'too long' => ['123456789012'],
    'wrong first digit' => ['52998224735'],
    'wrong check digits' => ['52998224720'],
    'letters' => ['abcdefghijk'],
    'non-string integer' => [12345678909],
    'empty string' => [''],
    'masked all equal' => ['111.111.111-11'],
    'wrong second digit' => ['52998224726'],
]);
