<?php

declare(strict_types=1);

namespace Kj8\DPD\Service;

use Kj8\DPD\DpdHttpClient;

final class ProtocolService
{
    public function __construct(private readonly DpdHttpClient $client)
    {
    }

    public function generateByWaybill(
        string $waybill,
        string $docFormat = 'PDF',
    ): string {
        $payload = [
            'protocolSearchParams' => [
                'policy' => 'IGNORE_ERRORS',
                'session' => [
                    'packages' => [
                        [
                            'parcels' => [
                                [
                                    'waybill' => $waybill,
                                ],
                            ],
                        ],
                    ],
                    'type' => 'DOMESTIC',
                ],
            ],
            'outputDocFormat' => $docFormat,
        ];

        $response = $this->client->post(
            '/public/shipment/v1/generateProtocol',
            $payload
        );

        return base64_decode((string) $response['documentData']);
    }
}
