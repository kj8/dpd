<?php

declare(strict_types=1);

namespace Kj8\DPD\Service;

use Kj8\DPD\DpdHttpClient;
use Kj8\DPD\DTO\Package;

final class ShipmentService
{
    public function __construct(private readonly DpdHttpClient $client)
    {
    }

    /**
     * @param Package[] $packages
     *
     * @return array<string, mixed>
     */
    public function generatePackages(array $packages): array
    {
        $payload = [
            'generationPolicy' => 'ALL_OR_NOTHING',
            'packages' => array_map(
                static fn (Package $p) => $p->toArray(),
                $packages
            ),
        ];

        return $this->client->post(
            '/public/shipment/v1/generatePackagesNumbers',
            $payload
        );
    }
}
