<?php

namespace Vormkracht10\KvKApi;

use Illuminate\Support\Collection;
use Vormkracht10\KvKApi\Company\Company;

class Client
{
    private $httpClient;
    private $baseUrl;
    private array $results;

    public function __construct($httpClient)
    {
        $this->httpClient = $httpClient;
        $this->baseUrl = 'https://api.kvk.nl/api/v2/';
    }

    public function getData(string $search)
    {
        $url = $this->baseUrl . 'zoeken?naam=' . urlencode($search);

        $response = $this->httpClient->get($url);

        return $this->getJson($response);
    }

    private function getJson(object $response)
    {
        return $response->getBody()->getContents();
    }

    private function decodeJson(string $json)
    {
        return json_decode($json);
    }

    public function search(string $search)
    {
        $data = $this->getData($search);

        $parsedData = $this->parseData($this->decodeJson($data));

        $parsedData->each(function ($item) {
            $data = json_decode($this->getRelatedData($item));

            $this->results[] = new Company(
                $data->kvkNummer ?? null,
                $data->vestigingsnummer ?? null,
                $data->naam ?? null, // Changed from eersteHandelsnaam to naam
                $data->adres ?? null, // Changed from adressen to adres
                $data->websites ?? null
            );
        });

        return $this->results;
    }

    private function parseData(object $data)
    {
        $data = collect($data->resultaten);

        $data = $data->map(function ($value) {
            $value = (object) $value;
            $value->attributes = collect((array) $value)->except(['type', 'links']);
            $value->id = uniqid();

            if (isset($value->links)) {
                $links = collect($value->links);
                $links = $links->mapWithKeys(function ($linkObj) {
                    return [$linkObj->rel => $linkObj->href];
                });
                $value->links = $links;
            } else {
                $value->links = collect();
            }

            return $value;
        });

        return $data;
    }

    private function getRelatedData($parsedData): Collection
    {
        $relatedData = collect();

        collect($parsedData->links)->each(function ($link, $key) use (&$relatedData) {
            $response = $this->httpClient->get($link);

            $data = $this->decodeJson($this->getJson($response));

            $relatedData = $relatedData->merge($data);
        });

        return $relatedData;
    }
}