<?php

declare(strict_types=1);

namespace Kj8\DPD\Service;

use Kj8\DPD\DpdHttpClient;
use Kj8\DPD\DTO\PackageRequest;

final class LabelService
{
    public function __construct(private readonly DpdHttpClient $client)
    {
    }

    public function generateSingle(
        PackageRequest $package,
    ): array {
        return $this->generateMultiple([$package]);
    }

    /**
     * @param PackageRequest[] $packages
     */
    public function generateMultiple(
        array $packages,
    ): array {
        $payload = [
            'labelSearchParams' => [
                'policy' => 'IGNORE_ERRORS',
                'session' => [
                    'type' => 'DOMESTIC',
                    'packages' => array_map(
                        static fn (PackageRequest $package) => $package->toArray(),
                        $packages
                    ),
                ],
            ],
            'outputDocFormat' => 'PDF',
            'format' => 'A4',
            'outputType' => 'BIC3',
            'variant' => 'STANDARD',
        ];

        return $this->client->post(
            '/public/shipment/v1/generateSpedLabels',
            $payload
        );

        // return base64_decode((string) $response['documentData']);
    }
}
