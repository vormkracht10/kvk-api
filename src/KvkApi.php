<?php

namespace Vormkracht10\KvkApi;

use GuzzleHttp\Client;

class KvkApi
{
    protected string $apiKey;
    protected string $baseUrl;
    protected string $url;
    protected string $certPath;

    public function __construct(string $apiKey, string $certPath)
    {
        $this->apiKey = $apiKey;
        $this->baseUrl = 'https://api.kvk.nl/test/api/v1/';
        $this->certPath = $certPath;
    }

    private function createHttpRequest($url): object
    {
        $http = new Client();

        $response = $http->request('GET', $url, [
            'headers' => [
                'apikey' => $this->apiKey,
            ],
            // 'cert' => $this->certPath,
            'verify' => false,
        ]);

        return json_decode($response->getBody()->getContents());
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
