# Brazilian Validation Rules for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/lumensistemas/br-validation-laravel.svg?style=flat-square)](https://packagist.org/packages/lumensistemas/br-validation-laravel)
[![Tests](https://img.shields.io/github/actions/workflow/status/lumensistemas/br-validation-laravel/package-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/lumensistemas/br-validation-laravel/actions/workflows/package-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/lumensistemas/br-validation-laravel.svg?style=flat-square)](https://packagist.org/packages/lumensistemas/br-validation-laravel)

A Laravel package that provides validation rules for Brazilian documents such as CPF and CNPJ (including the new alphanumeric format).

## Installation

You can install the package via composer:

```bash
composer require lumensistemas/br-validation-laravel
```

The service provider is auto-discovered by Laravel. Translations for `en` and `pt_BR` are included out of the box.

You can optionally publish the translation files:

```bash
php artisan vendor:publish --tag=br-validation-lang
```

## Usage

### CPF

```php
use LumenSistemas\BrValidation\Rules\Cpf;

$request->validate([
    'cpf' => ['required', new Cpf],
]);
```

Accepts both masked (`529.982.247-25`) and unmasked (`52998224725`) formats.

### CNPJ

```php
use LumenSistemas\BrValidation\Rules\Cnpj;

$request->validate([
    'cnpj' => ['required', new Cnpj],
]);
```

Accepts masked (`11.222.333/0001-81`), unmasked (`11222333000181`), and alphanumeric (`12ABC34501DE35`) formats.

## Testing

```bash
composer test:all
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/lumensistemas/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Lucas Vasconcelos](https://github.com/lucasvscn)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
