<?php

declare(strict_types=1);

namespace Kj8\DPD\DTO;

final class Package
{
    /** @var Parcel[] */
    private array $parcels = [];

    public function __construct(
        private readonly string $reference,
        private readonly Address $sender,
        private readonly Address $receiver,
        private readonly int $payerFid,
    ) {
    }

    public function addParcel(Parcel $parcel): void
    {
        $this->parcels[] = $parcel;
    }

    public function toArray(): array
    {
        return [
            'reference' => $this->reference,
            'sender' => $this->sender->toArray(),
            'receiver' => $this->receiver->toArray(),
            'payerFID' => $this->payerFid,
            'services' => [],
            'parcels' => array_map(
                fn (Parcel $p) => $p->toArray(),
                $this->parcels
            ),
        ];
    }
}
