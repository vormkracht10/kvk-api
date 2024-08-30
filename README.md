# KvK API client (Dutch Chamber of Commerce)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/vormkracht10/kvk-api.svg?style=flat-square)](https://packagist.org/packages/vormkracht10/kvk-api)
[![Tests](https://github.com/vormkracht10/kvk-api/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/vormkracht10/kvk-api/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/vormkracht10/kvk-api.svg?style=flat-square)](https://packagist.org/packages/vormkracht10/kvk-api)

PHP package to communicate with the business register of the Dutch Chamber of Commerce.

At the moment it is only possible to search by company name. The result will contain the following data:

<ul>
  <li>KvK number</li>
  <li>Establishment number</li>
  <li>Tradename</li>
  <li>Address(es) (type, full address, street, housenumber, zip, city and country)</li>
  <li>Website(s)</li>
</ul>

## Installation

You can install the package via composer:

```bash
composer require vormkracht10/kvk-api
```

## Usage

```php
use Vormkracht10\KvkApi\ClientFactory;

$apiKey = '<KVK_API_KEY>';
$rootCertificate = '<PATH_TO_SSL_CERT>'; // Optional

$kvk = ClientFactory::create($apiKey, $rootCertificate);

// Search by company name
$companies = $kvk->search('Vormkracht10');
```

> Note: if you don't have an API key yet, get yours at the [developer portal](https://developers.kvk.nl/) of the Chamber of Commerce

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

-   [Bas van Dinther](https://github.com/Baspa)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
