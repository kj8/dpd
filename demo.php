<?php

declare(strict_types=1);

include __DIR__.'/vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use Kj8\DPD\DpdConfig;
use Kj8\DPD\DpdHttpClient;
use Kj8\DPD\DTO\Address;
use Kj8\DPD\DTO\Package;
use Kj8\DPD\DTO\PackageRequest;
use Kj8\DPD\DTO\Parcel;
use Kj8\DPD\DTO\ParcelRequest;
use Kj8\DPD\Service\LabelService;
use Kj8\DPD\Service\ProtocolService;
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
$protocolService = new ProtocolService($client);

$sender = new Address(
    'Warszawa',
    '00001',
    'Ulica 1',
    'PL',
    'Jan Kowalski',
    '48123123123',
    'a@a.pl',
    'Firma A',
);

$receiver = new Address(
    'Kraków',
    '30001',
    'Ulica 2',
    'PL',
    'Anna Nowak',
    '48999111222',
    'b@b.pl',
    'Firma B',
);

$packageReference1 = 'ORDER_1-'.uniqid();
$parcelReference1 = 'PARCEL_1-'.uniqid();
$parcelReference2 = 'PARCEL_2-'.uniqid();

$packageReference2 = 'ORDER_2-'.uniqid();
$parcelReference3 = 'PARCEL_3-'.uniqid();
$parcelReference4 = 'PARCEL_4-'.uniqid();

$package1 = (new Package($sender, $receiver, 1495, $packageReference1))
    ->addParcel(new Parcel(1, $parcelReference1))
    ->addParcel(new Parcel(1, $parcelReference2));

$package2 = (new Package($sender, $receiver, 1495, $packageReference2))
    ->addParcel(new Parcel(1, $parcelReference3))
    ->addParcel(new Parcel(1, $parcelReference4));

$response = $shipmentService->generatePackages([$package1, $package2]);

file_put_contents(__DIR__.'/response-'.time().'.json', json_encode($response, \JSON_PRETTY_PRINT));

$packageRequest1 = (new PackageRequest())
    ->addParcel(new ParcelRequest($response['packages'][0]['parcels'][0]['waybill']))
    ->addParcel(new ParcelRequest($response['packages'][0]['parcels'][1]['waybill']));

$packageRequest2 = (new PackageRequest())
    ->addParcel(new ParcelRequest($response['packages'][1]['parcels'][0]['waybill']))
    ->addParcel(new ParcelRequest($response['packages'][1]['parcels'][1]['waybill']));

$labels = $labelService->generateMultiple([$packageRequest1, $packageRequest2]);

file_put_contents(__DIR__.'/etykiety_'.time().'.pdf', $labels);

$protocols = $protocolService->generateMultiple([$packageRequest1, $packageRequest2]);

file_put_contents(__DIR__.'/protocols_'.time().'.pdf', $protocols);
