<?php

declare(strict_types=1);

namespace Kj8\DPD\Service;

use Kj8\DPD\DpdHttpClient;

final class LabelService
{
    public function __construct(private readonly DpdHttpClient $client)
    {
    }

    public function generateBySession(
        int $sessionId,
        string $format = 'A4',
        string $docFormat = 'PDF',
    ): string {
        $payload = [
            'labelSearchParams' => [
                'policy' => 'STOP_ON_FIRST_ERROR',
                'session' => [
                    'sessionId' => $sessionId,
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

        return base64_decode((string) $response['documentData']);
    }
}
