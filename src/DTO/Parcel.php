<?php

declare(strict_types=1);

namespace Kj8\DPD\DTO;

final class Parcel
{
    public function __construct(
        public readonly string $reference,
        public readonly float $weight,
        public readonly float $sizeX,
        public readonly float $sizeY,
        public readonly float $sizeZ,
        public readonly float $weightAdr = 0.0,
    ) {
    }

    public function toArray(): array
    {
        return [
            'reference' => $this->reference,
            'weight' => $this->weight,
            'weightAdr' => $this->weightAdr,
            'sizeX' => $this->sizeX,
            'sizeY' => $this->sizeY,
            'sizeZ' => $this->sizeZ,
        ];
    }
}
