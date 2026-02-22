<?php

declare(strict_types=1);

namespace Kj8\DPD\Exception;

class DpdResponseException extends DpdException
{
    private function __construct(private readonly int $statusCode, private readonly string $body, string $message = '', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function create(int $statusCode, string $body, string $message = '', int $code = 0, ?\Throwable $previous = null): self
    {
        return new self($statusCode, $body, $message, $code, $previous);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getBody(): string
    {
        return json_decode($this->body, true, 512, \JSON_THROW_ON_ERROR);
    }
}
