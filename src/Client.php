<?php

namespace Vormkracht10\KvKApi;

use Illuminate\Support\Collection;
use Vormkracht10\KvKApi\Company\Company;

class Client
{
    private $httpClient;
    private $baseUrl;
    private array $results;
    private int $page;
    private int $resultsPerPage;

    public function __construct($httpClient)
    {
        $this->httpClient = $httpClient;
        $this->baseUrl = 'https://api.kvk.nl/api/v2/';
    }

    public function search(string $search, array $params = [])
    {
        $queryParams = array_merge([
            'naam' => $search,
            'pagina' => $this->page ?? 1,
            'resultatenPerPagina' => $this->resultsPerPage ?? 10
        ], $params);
        $data = $this->getData($queryParams);

        $parsedData = $this->parseData($this->decodeJson($data));

        $parsedData->each(function ($item) {
            $data = json_decode($this->getRelatedData($item));

            $this->results[] = new Company(
                $data->kvkNummer ?? null,
                $data->vestigingsnummer ?? null,
                $data->naam ?? null,
                $data->adres ?? null,
                $data->websites ?? null
            );
        });

        return $this->results;
    }

    private function getData(array $params)
    {
        $url = $this->baseUrl . 'zoeken?' . http_build_query($params);

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

    public function setPage(int $page)
    {
        $this->page = $page;
        return $this;
    }

    public function setResultsPerPage(int $resultsPerPage)
    {
        $this->resultsPerPage = $resultsPerPage;
        return $this;
    }

    public function searchByKvkNumber(string $kvkNumber, array $params = [])
    {
        return $this->search('', array_merge(['kvkNummer' => $kvkNumber], $params));
    }

    public function searchByRsin(string $rsin, array $params = [])
    {
        return $this->search('', array_merge(['rsin' => $rsin], $params));
    }

    public function searchByVestigingsnummer(string $vestigingsnummer, array $params = [])
    {
        return $this->search('', array_merge(['vestigingsnummer' => $vestigingsnummer], $params));
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

            $value->actief = $value->actief ?? null;
            $value->vervallenNaam = $value->vervallenNaam ?? null;

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