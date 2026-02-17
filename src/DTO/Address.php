<?php

declare(strict_types=1);

namespace Kj8\DPD\DTO;

final class Address
{
    public function __construct(
        public readonly string $company,
        public readonly string $name,
        public readonly string $address,
        public readonly string $city,
        public readonly string $countryCode,
        public readonly string $postalCode,
        public readonly string $phone,
        public readonly string $email,
    ) {
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
