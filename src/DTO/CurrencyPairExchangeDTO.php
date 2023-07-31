<?php

declare(strict_types=1);

namespace App\DTO;

use DateTimeInterface;
use JsonSerializable;

class CurrencyPairExchangeDTO implements JsonSerializable
{
    public function __construct(
        private readonly string $baseTag,
        private readonly string $baseName,
        private readonly string $quoteTag,
        private readonly string $quoteName,
        private readonly string $rate,
        private readonly DateTimeInterface $createdAt
    ) {
    }

    public function getBaseTag(): string
    {
        return $this->baseTag;
    }

    public function getBaseName(): string
    {
        return $this->baseName;
    }

    public function getQuoteTag(): string
    {
        return $this->quoteTag;
    }

    public function getQuoteName(): string
    {
        return $this->quoteName;
    }

    public function getRate(): string
    {
        return $this->rate;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function jsonSerialize(): array
    {
        return [
            "base" => [
                "tag" => $this->baseTag,
                "name" => $this->baseName
            ],
            "quote" => [
                "tag" => $this->quoteTag,
                "name" => $this->quoteName
            ],
            "rate" => $this->rate,
            "createdAt" => $this->createdAt->format("d/m/Y H:i:s P"),
        ];
    }
}