<?php

use LumenSistemas\BrValidation\Rules\Cnpj;

it('accepts valid cnpjs', function (string $cnpj): void {
    $rule = new Cnpj();
    $failed = false;

    $rule->validate('cnpj', $cnpj, failClosure($failed));

    expect($failed)->toBeFalse();
})->with([
    'numeric' => ['11222333000181'],
    'masked' => ['11.222.333/0001-81'],
    'all zeros prefix masked' => ['00.000.000/0001-91'],
    'all zeros prefix' => ['00000000000191'],
    'alphanumeric masked lowercase' => ['12.abc.345/01de-35'],
    'alphanumeric' => ['12abc34501de35'],
    'alphanumeric uppercase' => ['12ABC34501DE35'],
]);

it('rejects invalid cnpjs', function (mixed $cnpj): void {
    $rule = new Cnpj();
    $failed = false;

    $rule->validate('cnpj', $cnpj, failClosure($failed));

    expect($failed)->toBeTrue();
})->with([
    'wrong check digits' => ['11222333000182'],
    'too short' => ['1122233300018'],
    'too long' => ['112223330001811'],
    'non-string integer' => [11222333000181],
    'empty string' => [''],
    'letters only' => ['abcdefghijklmn'],
]);
