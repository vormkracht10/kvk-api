# Upgrading from v1.x to v2.x

## Changes in Search Results

In the new version, the search results have been expanded to include more detailed information. The Company object now contains the following data:

-   KvK number
-   Establishment number
-   Tradename
-   Address(es) (including type, full address, street, house number, zip code, city, and country)
-   Website(s)

## New Search Methods

1. Search by KvK Number
   The new version introduces the ability to search by KvK number:

```php
$companies = $kvk->searchByKvkNumber('12345678');
```

2. Search by RSIN
   You can now search by RSIN (Rechtspersonen en Samenwerkingsverbanden Informatienummer):

```php
$companies = $kvk->searchByRSIN('12345678');
```

## Pagination Support

The new version adds support for pagination in search results:

```php
$kvk->setPage(2);
$kvk->setResultsPerPage(20);
```

Alternatively, you can pass these parameters directly to the search method:

```php
$companies = $kvk->search('Vormkracht10', [
    'pagina' => 1,
    'resultatenPerPagina' => 10
]);
```

## Updating dependencies

```bash
{
    "require": {
        "vormkracht10/kvk-api": "^2.0"
    }
}
```

Then run `composer update` to install the new version.

## Breaking changes

-   The structure of the Company object has changed. Make sure to update any code that relies on the specific structure of the search results.
-   The method signatures for creating the client and performing searches have been updated. Review and update your code accordingly.
