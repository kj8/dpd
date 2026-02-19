<?php

declare(strict_types=1);

namespace Kj8\DPD\Service;

use Kj8\DPD\DpdHttpClient;

final class LabelService
{
    public function __construct(private readonly DpdHttpClient $client)
    {
    }

    public function generateSingle(
        string $waybill,
        string $format = 'A4',
        string $docFormat = 'PDF',
    ): string
    {
        return $this->generateMultiple([$waybill], $format, $docFormat);
    }

    /**
     * @param array $structure
     */
    public function generateMultiple(
        array $structure,
        string $format = 'A4',
        string $docFormat = 'PDF',
    ): string
    {
        $packages = [];
        foreach ($structure as $_package) {
            $parcels = [];
            foreach ($_package['parcels'] as $_parcel) {
                $parcel = [
                    'reference' => $_parcel['reference'],
                    'waybill' => $_parcel['waybill'],
                ];
                $parcels[] = $parcel;
            }
            $package = [
                'reference' => $_package['reference'],
                'parcels' => $parcels,
            ];
            $packages[] = $package;
        }

        $payload = [
            'labelSearchParams' => [
                'policy' => 'IGNORE_ERRORS',
                'session' => [
                    'packages' => $packages,
                    'type' => 'DOMESTIC',
                ],
            ],
            'outputDocFormat' => $docFormat,
            'format' => $format,
            'outputType' => 'BIC3',
            'variant' => 'STANDARD',
        ];

        $response = $this->client->post(
            '/public/shipment/v1/generateSpedLabels',
            $payload
        );

        return base64_decode((string)$response['documentData']);
    }
}
