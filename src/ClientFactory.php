<?php

namespace Vormkracht10\KvKApi;

use GuzzleHttp\Client;
use Swis\JsonApi\Client\Parsers\DocumentParser;
use Swis\JsonApi\Client\TypeMapper;
use Vormkracht10\KvKApi\Client as KvKApiClient;
use Vormkracht10\KvKApi\Models\Basisprofiel;
use Vormkracht10\KvKApi\Models\Hoofdvestiging;
use Vormkracht10\KvKApi\Models\Rechtspersoon;
use Vormkracht10\KvKApi\Models\Vestigingsprofiel;

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
