<?php

namespace Kj8\DPD\DTO;

final class ServiceAttributeDTO
{
    public function __construct(
        public readonly DpdServiceCode $code,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'code' => $this->code->value,
        ];
    }
}
