<?php

declare(strict_types=1);

namespace Kj8\DPD\DTO;

use Kj8\DPD\Exception\LabelPackageException;

/**
 * @phpstan-type ParcelRequestArray array{
 *     waybill: string,
 *     reference?: string
 * }
 */
final class ParcelRequest
{
    public function __construct(
        public readonly string $waybill,
        public readonly ?string $reference = null,
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        if (null !== $this->reference && mb_strlen($this->reference) > 50) {
            throw new LabelPackageException('Reference cannot be longer than 50 characters.');
        }
    }

    /**
     * @return ParcelRequestArray
     */
    public function toArray(): array
    {
        $data = [
            'waybill' => $this->waybill,
        ];

        if (null !== $this->reference) {
            $data['reference'] = $this->reference;
        }

        return $data;
    }
}
