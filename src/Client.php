<?php

namespace Vormkracht10\KvKApi;

use Illuminate\Support\Collection;

class Client
{
    private $httpClient;
    private $baseUrl;
        
    public function __construct($httpClient, $documentParser)
    {
        $this->httpClient = $httpClient;
        $this->baseUrl = 'https://api.kvk.nl/api/v1/';
        $this->documentParser = $documentParser;
    }

    public function search(string $search)
    {
        $url = $this->baseUrl . 'zoeken?handelsnaam=' . $search;

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

    public function fetchSearch(string $search)
    {
        $data = $this->search($search);

        $parsedData = $this->parseData($this->decodeJson($data));

        $parsedData->getData()->each(function ($item) {
            dd($this->getRelatedData($item));
        });
    }

    private function parseData(object $data)
    {
        $data = collect($data->resultaten);

        $data = $data->map(function ($value, $key) {
            // Set attributes
            $value->attributes = collect($value)->except(['type', 'links']);

            // Set unique id
            $value->id = uniqid();

            // Remove all things in attributes that are inside $value
            $value = collect($value)->except($value->attributes->keys());

            // Set links
            $links = collect($value['links']);

            $links = $links->mapWithKeys(function ($value, $key) {
                return [$value->rel => $value->href];
            });

            $value['links'] = $links;

            return $value;
        });

        $object = new \stdClass();

        $object->data = $data;

        return $this->documentParser->parse(json_encode($object));
    }

    private function getRelatedData($parsedData): Collection
    {
        $relatedData = collect();

        collect($parsedData->getLinks())->each(function ($link, $key) use (&$relatedData) {
            $response = $this->httpClient->get($link['href']);

            $data = $this->getJson($response);

            $relatedData[$key] = $data;
        });

        return $relatedData;
    }
}
