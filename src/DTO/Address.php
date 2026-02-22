<?php

declare(strict_types=1);

namespace Kj8\DPD\DTO;

use Kj8\DPD\Exception\AddressException;

/**
 * @phpstan-type AddressArray array{
 *     city: string,
 *     postalCode: string,
 *     address: string,
 *     countryCode: string,
 *     name?: string,
 *     phone?: string,
 *     email?: string,
 *     company?: string
 * }
 */
final class Address
{
    public function __construct(
        public readonly string $city,
        public readonly string $postalCode,
        public readonly string $address,
        public readonly string $countryCode,
        public readonly ?string $name = null,
        public readonly ?string $phone = null,
        public readonly ?string $email = null,
        public readonly ?string $company = null,
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        if (mb_strlen($this->city) > 50) {
            throw new AddressException('City cannot be longer than 50 characters.');
        }

        $postalLength = mb_strlen($this->postalCode);
        if ($postalLength < 4 || $postalLength > 10) {
            throw new AddressException('Postal code must be between 4 and 10 characters.');
        }

        if (mb_strlen($this->address) > 100) {
            throw new AddressException('Address cannot be longer than 100 characters.');
        }

        if (2 !== mb_strlen($this->countryCode)) {
            throw new AddressException('Country code must be exactly 2 characters (ISO ALPHA-2).');
        }

        if (!ctype_alpha($this->countryCode)) {
            throw new AddressException('Country code must contain only letters (ISO ALPHA-2).');
        }

        if (null !== $this->name && mb_strlen($this->name) > 100) {
            throw new AddressException('Name cannot be longer than 100 characters.');
        }

        if (null !== $this->phone && mb_strlen($this->phone) > 100) {
            throw new AddressException('Phone cannot be longer than 100 characters.');
        }

        if (null !== $this->email) {
            if (mb_strlen($this->email) > 100) {
                throw new AddressException('Email cannot be longer than 100 characters.');
            }

            if (!filter_var($this->email, \FILTER_VALIDATE_EMAIL)) {
                throw new AddressException('Email format is invalid.');
            }
        }

        if (null !== $this->company && mb_strlen($this->company) > 100) {
            throw new AddressException('Company cannot be longer than 100 characters.');
        }
    }

    /**
     * @return AddressArray
     */
    public function toArray(): array
    {
        $data = [
            'city' => $this->city,
            'postalCode' => $this->postalCode,
            'address' => $this->address,
            'countryCode' => $this->countryCode,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'company' => $this->company,
        ];

        return array_filter($data, static fn ($v) => null !== $v);
    }
}
