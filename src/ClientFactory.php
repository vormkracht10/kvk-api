<?php

namespace Vormkracht10\KvKApi;

use GuzzleHttp\Client;
use Swis\JsonApi\Client\TypeMapper;
use Vormkracht10\KvKApi\SecondClient;
use Vormkracht10\KvKApi\Models\Basisprofiel;
use Vormkracht10\KvKApi\Models\Rechtspersoon;
use Vormkracht10\KvKApi\Models\Hoofdvestiging;
use Swis\JsonApi\Client\Parsers\DocumentParser;
use Vormkracht10\KvKApi\Client as KvKApiClient;
use Vormkracht10\KvKApi\Models\Vestigingsprofiel;

class ClientFactory
{
    public static function create(string $apiKey, string $rootCertificate): KvKApiClient {
        return new KvKApiClient(
            self::createHttpClient($apiKey, $rootCertificate),
            self::createDocumentParser()
        );
    }

    private static function createHttpClient(
        string $apiKey,
        string $rootCertificate
    ){
        $client = new Client([
            'headers' => [
                'apikey' => $apiKey,
            ],
            'verify' => $rootCertificate,
        ]);

        return $client;
    }

    private static function createDocumentParser() {

        $typeMapper = new TypeMapper();

        // Get all classes from Models directory
        // $classes = collect(glob(__DIR__ . '/Models/*.php'))
        //     ->map(function ($file) {
        //         return basename($file, '.php');
        //     })
        //     ->map(function ($class) {
        //         return 'Vormkracht10\\KvKApi\Models\\' . $class;
        //     })
        //     ->map(function ($class) {
        //         dd($class);
        //         return new $class();
        //     });
        
        // Set mapping for each class
        // $classes->each(function ($class) use ($typeMapper) {
        //     $typeMapper->setMapping($class->getType(), $class);
        // });

        $typeMapper->setMapping('basisprofiel', Basisprofiel::class);
        $typeMapper->setMapping('vestigingsprofiel', Vestigingsprofiel::class);
        $typeMapper->setMapping('hoofdvestiging', Hoofdvestiging::class);
        $typeMapper->setMapping('rechtspersoon', Rechtspersoon::class);

        return DocumentParser::create($typeMapper);
    }
}
