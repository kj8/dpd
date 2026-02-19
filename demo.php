<?php

declare(strict_types=1);

include __DIR__ . '/vendor/autoload.php';

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

$packageReference1 = 'ORDER_1-' . uniqid();
$parcelReference1 = 'PARCEL_1-' . uniqid();
$parcelReference2 = 'PARCEL_2-' . uniqid();

$packageReference2 = 'ORDER_2-' . uniqid();
$parcelReference3 = 'PARCEL_3-' . uniqid();
$parcelReference4 = 'PARCEL_4-' . uniqid();

$package1 = new Package($packageReference1, $sender, $receiver, 1495);
$package1->addParcel(new Parcel($parcelReference1, 10, 10, 10, 10));
$package1->addParcel(new Parcel($parcelReference2, 10, 10, 10, 10));

$package2 = new Package($packageReference2, $sender, $receiver, 1495);
$package2->addParcel(new Parcel($parcelReference3, 10, 10, 10, 10));
$package2->addParcel(new Parcel($parcelReference4, 10, 10, 10, 10));

$response = $shipmentService->generatePackages([$package1, $package2]);

file_put_contents(__DIR__ . '/response.json', json_encode($response, JSON_PRETTY_PRINT));

$packages = [];

$pdfBinary = $labelService->generateMultiple($response['packages']);

file_put_contents(__DIR__ . '/etykiety_' . uniqid('', true) . '.pdf', $pdfBinary);
