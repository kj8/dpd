<?php

declare(strict_types=1);

namespace Kj8\DPD;

final class DpdConfig
{
    public readonly string $baseUri;

    public function __construct(
        public readonly string $login,
        public readonly string $password,
        public readonly int $fid,
        bool $isDemo = true,
    ) {
        $this->baseUri = $isDemo
            ? 'https://dpdservicesdemo.dpd.com.pl'
            : 'https://dpdservices.dpd.com.pl';
    }
}
