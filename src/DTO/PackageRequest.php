<?php

declare(strict_types=1);

namespace Kj8\DPD\DTO;

use Kj8\DPD\Exception\LabelParcelException;

/**
 * @phpstan-import-type ParcelRequestArray from ParcelRequest
 */
final class PackageRequest
{
    /**
     * @var ParcelRequest[]
     */
    private array $parcels = [];

    public function __construct(
        public readonly ?string $reference = null,
    ) {
        $this->validate();
    }

    public function addParcel(ParcelRequest $parcel): self
    {
        $this->parcels[] = $parcel;

        return $this;
    }

    private function validate(): void
    {
        if (null !== $this->reference && mb_strlen($this->reference) > 50) {
            throw new LabelParcelException('Reference cannot be longer than 50 characters.');
        }
    }

    /**
     * @return array{
     *     parcels: ParcelRequestArray[],
     *     reference?: string
     * }
     */
    public function toArray(): array
    {
        $data = [
            'parcels' => array_map(
                static fn (ParcelRequest $parcel) => $parcel->toArray(),
                $this->parcels
            ),
        ];

        if (null !== $this->reference) {
            $data['reference'] = $this->reference;
        }

        return $data;
    }
}
