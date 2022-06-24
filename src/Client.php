<?php

namespace Vormkracht10\KvKApi;

use Illuminate\Support\Collection;
use Swis\JsonApi\Client\TypeMapper;
use GuzzleHttp\Client as GuzzleClient;
use Swis\JsonApi\Client\Parsers\DocumentParser;

class Client
{
    protected string $apiKey;
    protected string $baseUrl;
    protected string $rootCertificate;
    protected DocumentParser $documentParser;
    protected TypeMapper $typeMapper;

    public function __construct(string $apiKey, string $rootCertificate)
    {
        $this->apiKey = $apiKey;
        $this->baseUrl = 'https://api.kvk.nl/api/v1/';
        $this->rootCertificate = $rootCertificate;

        $this->typeMapper = new TypeMapper();

        $this->typeMapper->setMapping('basisprofiel', Basisprofiel::class);
        $this->typeMapper->setMapping('vestigingsprofiel', Vestigingsprofiel::class);
        $this->typeMapper->setMapping('hoofdvestiging', Hoofdvestiging::class);
        $this->typeMapper->setMapping('rechtspersoon', Rechtspersoon::class);

        $this->documentParser = DocumentParser::create($this->typeMapper);
    }

    private function createHttpRequest(string $url): string
    {
        $http = new GuzzleClient();

        $response = $http->request('GET', $url, [
            'headers' => [
                'apikey' => $this->apiKey,
            ],
            'verify' => $this->rootCertificate ?? false,
        ]);

        return $response->getBody()->getContents();
    }

    public function search(string $companyName)
    {
        $url = $this->baseUrl . 'zoeken?handelsnaam=' . $companyName;

        $response = $this->createHttpRequest($url);

        $parsedData = $this->parseData($response);

        $data = $parsedData->getData();

        $data->each(function ($entity) {
            dd($this->getRelatedData($entity));
        });

        return $this->createHttpRequest($url);
    }

    // public function getBasicProfile(string $kvkNumber): object
    // {
    //     $url = $this->baseUrl . 'basisprofielen/' . $kvkNumber;

    //     return $this->createHttpRequest($url);
    // }

    // public function getEstablishmentProfile(string $locationNumber): object
    // {
    //     $url = $this->baseUrl . 'vestigingsprofielen/' . $locationNumber;

    //     return $this->createHttpRequest($url);
    // }

    private function getRelatedData($parsedData) : Collection
    {
        $relatedData = collect();

        collect($parsedData->getLinks())->each(function ($link, $key) use (&$relatedData) {

            $response = $this->createHttpRequest($link['href']);
            $relatedData[$key] = json_decode($response, true);

        });
    
        return $relatedData;
    }

    private function getIdentifier(string $type, Collection $data)
    {
        switch ($type) {
            case 'basisprofiel':
                return $data['attributes']->get('kvkNummer');
                break;
            case 'vestigingsprofiel':
                return $data['attributes']->get('vestigingsnummer');
                break;
            default:
                throw new \Exception('Unknown type');
                break;
        }
    }

    private function parseData(string $response): object
    {
        $response = json_decode($response, true);

        $data = collect($response['resultaten']);

        $data = $data->map(function ($value, $key) {
            // Set attributes
            $value['attributes'] = collect($value)->except(['type', 'links']);

            // Set unique id
            $value['id'] = uniqid();

            // Remove all things in attributes that are inside $value
            $value = collect($value)->except($value['attributes']->keys());

            // Set links
            $links = collect($value['links']);

            $links = $links->mapWithKeys(function ($value, $key) {
                return [$value['rel'] => $value['href']];
            });

            $value['links'] = $links;

            // Define relationships
            $value['relationships'] = $links->map(function ($link, $key) use ($value) {                
                return [
                    'data' => [
                        'type' => $key,
                        'id' => $this->getIdentifier($key, $value),
                    ],
                    'links' => [
                        'self' => $link,
                    ]
                ];
            });

            return $value;
        });

        $object = new \stdClass();
        
        $object->data = $data;

        return $this->documentParser->parse(json_encode($object));
    }
}
