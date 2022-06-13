
# KvK API client (Dutch Chamber of Commerce)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/vormkracht10/kvk-api.svg?style=flat-square)](https://packagist.org/packages/vormkracht10/kvk-api)
[![Tests](https://github.com/vormkracht10/kvk-api/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/vormkracht10/kvk-api/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/vormkracht10/kvk-api.svg?style=flat-square)](https://packagist.org/packages/vormkracht10/kvk-api)

PHP package to communicate with the business register of the Dutch Chamber of Commerce. The following APIs are available:

* Chamber of Commerce Trade Register Search (KVK Handelsregister Zoeken)
* Chamber of Commerce Trade Register Basic Profile (KVK Handelsregister Basisprofiel)
* Chamber of Commerce Trade Register Establishment Profile (KVK Handelsregister Vestigingsprofiel)

## Installation

You can install the package via composer:

```bash
composer require vormkracht10/kvk-api
```

## Usage

```php
use Vormkracht10\KvkApi\Client;

$apiKey = '<KVK_API_KEY>';
$rootCertificate = '<PATH_TO_SSL_CERT>';

$kvk = (new Client($apiKey, $rootCertificate));

// Search by company name
$companies = $kvk->search('Vormkracht10');

// Search basic profile by KvK number
$basicProfile = $kvk->getBasicProfile('76558606')

// Search establishment profile by establishment number
$locationProfile = $kvk->getEstablishmentProfile('000044332491')

```
> Note: if you don't have an API key yet, get yours at the [developer portal](https://developers.kvk.nl/) of the Chamber of Commerce


## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Bas van Dinther](https://github.com/Baspa)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
