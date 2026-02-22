<?php

declare(strict_types=1);

namespace Kj8\DPD\DTO;

/**
 * @phpstan-import-type AddressArray from Address
 * @phpstan-import-type ParcelArray from Parcel
 */
final class Package
{
    /** @var Parcel[] */
    private array $parcels = [];

    public function __construct(
        private readonly Address $sender,
        private readonly Address $receiver,
        private readonly int $payerFID,
        private readonly ?string $reference = null,
    ) {
    }

    public function addParcel(Parcel $parcel): self
    {
        $this->parcels[] = $parcel;

        return $this;
    }

    /**
     * @return array{
     *     sender: AddressArray,
     *     receiver: AddressArray,
     *     payerFID: int,
     *     parcels: ParcelArray[],
     *     reference?: string
     * }
     */
    public function toArray(): array
    {
        $data = [
            'sender' => $this->sender->toArray(),
            'receiver' => $this->receiver->toArray(),
            'payerFID' => $this->payerFID,
            'parcels' => array_map(
                static fn (Parcel $p) => $p->toArray(),
                $this->parcels
            ),
        ];

        if (null !== $this->reference) {
            $data['reference'] = $this->reference;
        }

        return $data;
    }
}
