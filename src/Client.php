<?php

namespace Vormkracht10\KvKApi;

use GuzzleHttp\ClientInterface;
use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface;
use stdClass;
use Vormkracht10\KvKApi\Company\Company;

class Client
{
    private ClientInterface $httpClient;
    private string $baseUrl;
    /** @var array<Company> */
    private array $results = [];
    private int $page = 1;
    private int $resultsPerPage = 10;

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->baseUrl = 'https://api.kvk.nl/api/v2/';
    }

    /**
     * @param array<string, mixed> $params
     * @return array<Company>
     */
    public function search(string $search, array $params = []): array
    {
        $queryParams = array_merge([
            'naam' => $search,
            'pagina' => $this->page,
            'resultatenPerPagina' => $this->resultsPerPage,
        ], $params);
        $data = $this->getData($queryParams);

        $parsedData = $this->parseData($this->decodeJson($data));

        foreach ($parsedData as $item) {
            $data = $this->decodeJson($this->getRelatedData($item));

            $this->results[] = new Company(
                $data->kvkNummer ?? '',
                $data->vestigingsnummer ?? null,
                $data->naam ?? null,
                $data->adressen ?? null,
                $data->websites ?? null
            );
        };

        return $this->results;
    }

    /**
     * @param array<string, mixed> $params
     */
    private function getData(array $params): string
    {
        $url = $this->baseUrl . 'zoeken?' . http_build_query($params);

        $response = $this->httpClient->request('GET', $url);

        return $this->getJson($response);
    }

    private function getJson(ResponseInterface $response): string
    {
        return (string) $response->getBody()->getContents();
    }

    /**
     * @return stdClass
     */
    private function decodeJson(string $json): stdClass
    {
        return json_decode($json, false) ?: new stdClass();
    }

    public function setPage(int $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function setResultsPerPage(int $resultsPerPage): self
    {
        $this->resultsPerPage = $resultsPerPage;

        return $this;
    }

    /**
     * @param array<string, mixed> $params
     * @return array<Company>
     */
    public function searchByKvkNumber(string $kvkNumber, array $params = []): array
    {
        return $this->search('', array_merge(['kvkNummer' => $kvkNumber], $params));
    }

    /**
     * @param array<string, mixed> $params
     * @return array<Company>
     */
    public function searchByRsin(string $rsin, array $params = []): array
    {
        return $this->search('', array_merge(['rsin' => $rsin], $params));
    }

    /**
     * @param array<string, mixed> $params
     * @return array<Company>
     */
    public function searchByVestigingsnummer(string $vestigingsnummer, array $params = []): array
    {
        return $this->search('', array_merge(['vestigingsnummer' => $vestigingsnummer], $params));
    }

    /**
     * @return array<int, stdClass>
     */
    private function parseData(stdClass $data): array
    {
        $resultaten = $data->resultaten ?? [];
        /** @var array<int, stdClass> $resultatenArray */
        $resultatenArray = is_array($resultaten) ? $resultaten : [];

        return array_map(function ($value) {
            $value = (object) $value;
            /** @var array<string, mixed> $attributes */
            $attributes = array_diff_key((array) $value, array_flip(['type', 'links']));
            $value->attributes = $attributes;
            $value->id = uniqid();

            if (isset($value->links)) {
                /** @var array<stdClass> $links */
                $links = $value->links;
                /** @var array<string, string> $mappedLinks */
                $mappedLinks = array_column($links, 'href', 'rel');
                $value->links = $mappedLinks;
            } else {
                /** @var array<string, string> $emptyLinks */
                $emptyLinks = [];
                $value->links = $emptyLinks;
            }

            $value->actief = $value->actief ?? null;
            $value->vervallenNaam = $value->vervallenNaam ?? null;

            return $value;
        }, $resultatenArray);
    }

    private function getRelatedData(stdClass $parsedData): string
    {
        $relatedData = [];

        /** @var Collection<string, string> $links */
        $links = collect((array)($parsedData->links ?? []));

        $links->each(function (string $link) use (&$relatedData) {
            $response = $this->httpClient->request('GET', $link);

            $data = $this->decodeJson($this->getJson($response));

            $relatedData = array_merge($relatedData, (array) $data);
        });

        return json_encode($relatedData) ?: '{}';
    }
}
