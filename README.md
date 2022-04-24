# 🥨 Joth laravel - Encrypt and decrypt requests and responses data

<p align="center">
<a href="LICENSE"><img src="https://img.shields.io/badge/license-MIT-lightgrey.svg" alt="License"></a>
<a href="https://packagist.org/packages/octha/joth-laravel"><img src="https://img.shields.io/packagist/v/octha/joth-laravel" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/octha/joth-laravel"><img src="https://img.shields.io/packagist/dt/octha/joth-laravel" alt="Total Downloads"></a>
</p>

## Why use this?

If you need to transit sensitive data you know leaving it in plaintext is not ideal, so using a layer that makes the data more difficult to capture is essential.

## Installation

```bash
composer require octha/joth-laravel
```

## Configuration

Publish the config file to your config folder.

```bach
php artisan vendor:publish --tag=octha/joth-laravel/config
```

Set a env variable `JOTH_KEY` with your key.

### Usage

This package needs a front-end layer. See the [joth jquery](https://github.com/octhahq/joth-jquery).

After includes the front-end layer, set a secret as same defined on your configuration file.

## Extra

If you need modify the middlewares, publish them

```bach
php artisan vendor:publish --tag=octha/joth-laravel/middlewares
```

Not is necessary add the middlewares to your routes or on kernel.

## License

This package is licensed under the [MIT license](LICENSE) © [Octha](https://octha.com).
