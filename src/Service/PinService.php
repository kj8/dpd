<?php

declare(strict_types=1);

namespace Kj8\DPD\Service;

use Kj8\DPD\DpdHttpClient;
use Kj8\DPD\DTO\PackageRequest;
use Kj8\DPD\DTO\ParcelRequest;

final class PinService
{
    public function __construct(private readonly DpdHttpClient $client)
    {
    }

    public function generateByWaybill(
        string $waybill,
    ): string {
        $package = (new PackageRequest())->addParcel(new ParcelRequest($waybill));

        return $this->generateMultiple([$package]);
    }

    /**
     * @param PackageRequest[] $packages
     */
    public function generateMultiple(
        array $packages,
        int $validityPeriod = 14,
    ): string {
        $payload = [
            'labelSearchParams' => [
                'policy' => 'IGNORE_ERRORS',
                'session' => [
                    'type' => 'DOMESTIC',
                    'packages' => array_map(
                        static fn ($package) => $package->toArray(),
                        $packages
                    ),
                ],
                'validityPeriod' => $validityPeriod,
            ],
        ];

        $response = $this->client->post(
            '/public/shipment/v1/generateDropOffPin',
            $payload
        );

        return base64_decode((string) $response['documentData']);
    }
}
