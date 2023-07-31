<?php

declare(strict_types=1);

namespace App\DTO;

use JsonSerializable;

class CurrencyDTO implements JsonSerializable
{
    public function __construct(
        private readonly string $tag,
        private readonly string $name,
        private readonly bool $isCrypto
    ) {
    }
    public function getTag(): string
    {
        return $this->tag;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getIsCrypto(): bool
    {
        return $this->isCrypto;
    }

    public function jsonSerialize(): array
    {
        return [
            'tag' => $this->tag,
            'name' => $this->name,
            'isCrypto' => $this->isCrypto,
        ];
    }
}