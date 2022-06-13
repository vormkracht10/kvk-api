<?php

namespace Vormkracht10\KvKApi;

use GuzzleHttp\Client as GuzzleClient;

class Client
{
    protected string $apiKey;
    protected string $baseUrl;
    protected string $rootCertificate;

    public function __construct(string $apiKey, string $rootCertificate)
    {
        $this->apiKey = $apiKey;
        $this->baseUrl = 'https://api.kvk.nl/api/v1/';
        $this->rootCertificate = $rootCertificate;
    }

    private function createHttpRequest(string $url): object
    {
        $http = new GuzzleClient();

        $response = $http->request('GET', $url, [
            'headers' => [
                'apikey' => $this->apiKey,
            ],
            'verify' => $this->rootCertificate ?? false,
        ]);

        return json_decode($response->getBody()->getContents());
    }

    public function search(string $companyName): object
    {
        $url = $this->baseUrl . 'zoeken?handelsnaam=' . $companyName;

        return $this->createHttpRequest($url);
    }

    public function getBasicProfile(string $kvkNumber): object
    {
        $url = $this->baseUrl . 'basisprofielen/' . $kvkNumber;

        return $this->createHttpRequest($url);
    }

    public function getEstablishmentProfile(string $locationNumber): object
    {
        $url = $this->baseUrl . 'vestigingsprofielen/' . $locationNumber;

        return $this->createHttpRequest($url);
    }
}
