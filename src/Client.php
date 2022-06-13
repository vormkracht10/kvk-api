<?php

namespace Vormkracht10\KvKApi;

use GuzzleHttp\Client;

class Client
{
    protected string $apiKey;
    protected string $baseUrl;
    protected string $url;
    protected string $rootCertificate;

    public function __construct(string $apiKey, string $rootCertificate)
    {
        $this->apiKey = $apiKey;
        $this->baseUrl = 'https://api.kvk.nl/api/v1/';
        $this->rootCertificate = $rootCertificate;
    }

    private function createHttpRequest($url): object
    {
        $http = new Client();

        $response = $http->request('GET', $url, [
            'headers' => [
                'apikey' => $this->apiKey,
            ],
            'verify' => $this->rootCertificate ?? false,
        ]);

        return $this->getJson($response->getBody()->getContents());
    }

    public function getJson(string $data): object
    {
        return json_decode($data);
    }

    public function search(string $companyName): object
    {
        $this->url = $this->baseUrl . 'zoeken?handelsnaam=' . $companyName;

        return $this->createHttpRequest($this->url);
    }

    public function getBasicProfile(string $kvkNumber): object
    {
        $this->url = $this->baseUrl . 'basisprofielen/' . $kvkNumber;

        return $this->createHttpRequest($this->url);
    }

    public function getLocationProfile(string $locationNumber): object
    {
        $this->url = $this->baseUrl . 'vestigingsprofielen/' . $locationNumber;

        return $this->createHttpRequest($this->url);
    }
}
