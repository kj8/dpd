<?php

declare(strict_types=1);

include __DIR__.'/vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use Kj8\DPD\DpdConfig;
use Kj8\DPD\DpdHttpClient;
use Kj8\DPD\DTO\Address;
use Kj8\DPD\DTO\Package;
use Kj8\DPD\DTO\Parcel;
use Kj8\DPD\Service\LabelService;
use Kj8\DPD\Service\ShipmentService;

$config = new DpdConfig(
    login: 'test',
    password: 'thetu4Ee',
    fid: 1495,
    isDemo: true,
);

$httpClient = new Client();
$requestFactory = new HttpFactory();

$client = new DpdHttpClient($httpClient, $requestFactory, $config);

$shipmentService = new ShipmentService($client);
$labelService = new LabelService($client);

$sender = new Address(
    'Firma A',
    'Jan Kowalski',
    'Ulica 1',
    'Warszawa',
    'PL',
    '00001',
    '48123123123',
    'a@a.pl'
);

$receiver = new Address(
    'Firma B',
    'Anna Nowak',
    'Ulica 2',
    'Kraków',
    'PL',
    '30001',
    '48999111222',
    'b@b.pl'
);

$packageReference = 'ORDER-5';
$parcelReference = 'PARCEL-5';

$package = new Package($packageReference, $sender, $receiver, 1495);
$package->addParcel(new Parcel($parcelReference, 10, 10, 10, 10));

$response = $shipmentService->generatePackages([$package]);

$sessionId = $response['sessionId'];

$pdfBinary = $labelService->generateBySession($sessionId);

file_put_contents('etykieta_'.uniqid('', true).'.pdf', $pdfBinary);
