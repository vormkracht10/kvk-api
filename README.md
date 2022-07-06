
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
use Vormkracht10\KvkApi\ClientFactory;

$apiKey = '<KVK_API_KEY>';
$rootCertificate = '<PATH_TO_SSL_CERT>';

$kvk = ClientFactory::create($apiKey, $rootCertificate);

$companies = $kvk->fetchSearch('Vormkracht10');

$kvk = (new Client($apiKey, $rootCertificate));

// Search by company name
$companies = $kvk->search('Vormkracht10');

// Result:
Vormkracht10\KvKApi\Company\Company {
  -kvkNumber: "76558606"
  -establishmentNumber: "000044332491"
  -tradeName: "Vormkracht10 B.V."
  -addresses: array:1 [
    0 => {#829
      +"type": "bezoekadres"
      +"indAfgeschermd": "Nee"
      +"volledigAdres": "St. Annastraat 175 6524EV Nijmegen"
      +"straatnaam": "St. Annastraat"
      +"huisnummer": 175
      +"postcode": "6524EV"
      +"plaats": "Nijmegen"
      +"land": "Nederland"
    }
  ]
  -websites: array:1 [
    0 => "www.vormkracht10.nl"
  ]
}

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
