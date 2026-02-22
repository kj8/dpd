<?php

declare(strict_types=1);

namespace Kj8\DPD\DTO;

use Kj8\DPD\Exception\ParcelException;

/**
 * @phpstan-type ParcelArray array{
 *     weight: float,
 *     reference?: string,
 *     sizeX?: float,
 *     sizeY?: float,
 *     sizeZ?: float,
 *     content?: string,
 *     weightAdr?: float
 * }
 */
final class Parcel
{
    public function __construct(
        public readonly float $weight,
        public readonly ?string $reference = null,
        public readonly ?float $sizeX = null,
        public readonly ?float $sizeY = null,
        public readonly ?float $sizeZ = null,
        public readonly ?string $content = null,
        public readonly ?float $weightAdr = null,
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        if ($this->weight < 0.01 || $this->weight > 1000) {
            throw new ParcelException('Weight must be between 0.01 and 1000.');
        }

        if (null !== $this->reference && mb_strlen($this->reference) > 50) {
            throw new ParcelException('Reference cannot be longer than 50 characters.');
        }

        if (null !== $this->sizeX && ($this->sizeX < 1 || $this->sizeX > 300)) {
            throw new ParcelException('SizeX must be between 1 and 300.');
        }

        if (null !== $this->sizeY && ($this->sizeY < 1 || $this->sizeY > 300)) {
            throw new ParcelException('SizeY must be between 1 and 300.');
        }

        if (null !== $this->sizeZ && ($this->sizeZ < 1 || $this->sizeZ > 300)) {
            throw new ParcelException('SizeZ must be between 1 and 300.');
        }

        if (null !== $this->content && mb_strlen($this->content) > 300) {
            throw new ParcelException('Content cannot be longer than 300 characters.');
        }

        if (null !== $this->weightAdr && ($this->weightAdr < 0 || $this->weightAdr > 700)) {
            throw new ParcelException('WeightAdr must be between 0 and 700.');
        }
    }

    /**
     * @return ParcelArray
     */
    public function toArray(): array
    {
        $data = [
            'weight' => $this->weight,
            'reference' => $this->reference,
            'sizeX' => $this->sizeX,
            'sizeY' => $this->sizeY,
            'sizeZ' => $this->sizeZ,
            'content' => $this->content,
            'weightAdr' => $this->weightAdr,
        ];

        return array_filter($data, static fn ($v) => null !== $v);
    }
}
