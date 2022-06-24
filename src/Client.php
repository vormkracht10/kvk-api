<?php

namespace Vormkracht10\KvKApi;

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

        // Change resultaten to data
        $response = json_decode($response, true);

        $data = collect($response['resultaten']);

        $data = $data->map(function ($value, $key) {

            // Set unique id
            $value['id'] = uniqid();

            // Set attributes
            $value['attributes'] = (object) collect($value)->except(['type', 'links']);

            // Remove all things in attributes that are inside $value
            $value = collect($value)->except($value['attributes']->keys());

            // Set links
            $links = collect($value['links']);

            $links = $links->mapWithKeys(function ($value, $key) {
                return [$value['rel'] => $value['href']];
            });

            $value['links'] = $links;

            // Define relationships
            $value['relationships'] = $links->map(function ($link, $key){
                return [
                    'data' => [
                        'type' => $key,
                        'id' => uniqid(),
                    ],
                    'links' => [
                        'self' => $link,
                    ]
                ];
            });

            return $value;
        });

        // $response = json_encode($data);

        dd($data->toJson());

        dd($this->documentParser->parse($data->toJson()));

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
}
