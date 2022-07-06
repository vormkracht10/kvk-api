<?php

namespace Vormkracht10\KvKApi;

use GuzzleHttp\Client;
use Vormkracht10\KvKApi\Client as KvKApiClient;

class ClientFactory
{
    public static function create(string $apiKey, string $rootCertificate): KvKApiClient
    {
        return new KvKApiClient(
            self::createHttpClient($apiKey, $rootCertificate)
        );
    }

    private static function createHttpClient(
        string $apiKey,
        string $rootCertificate
    ) {
        $client = new Client([
            'headers' => [
                'apikey' => $apiKey,
            ],
            'verify' => $rootCertificate,
        ]);

        return $client;
    }
}
