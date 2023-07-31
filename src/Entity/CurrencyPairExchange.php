<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CurrencyPairExchangeRepository;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Table(name: "currency_pair_exchange")]
#[ORM\Entity(repositoryClass: CurrencyPairExchangeRepository::class)]
class CurrencyPairExchange
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid")]
    private UuidInterface $id;

    #[ORM\ManyToOne(targetEntity: Currency::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private Currency $base;

    #[ORM\ManyToOne(targetEntity: Currency::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private Currency $quote;

    #[ORM\Column(type: "string", length: 180, nullable: false)]
    private string $rate;

    #[ORM\Column(type: "datetime", nullable: false)]
    private DateTimeInterface $createdAt;

    public function __construct(Currency $base, Currency $quote, string $rate)
    {
        $this->id = Uuid::uuid6();
        $this->base = $base;
        $this->quote = $quote;
        $this->rate = $rate;

        try {
            $this->createdAt = new DateTimeImmutable(timezone: new DateTimeZone("Europe/Riga"));
        } catch (Exception $e) {
            error_log('Error creating DateTimeImmutable objects: ' . $e->getMessage());
        }
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getBase(): Currency
    {
        return $this->base;
    }

    public function getQuote(): Currency
    {
        return $this->quote;
    }

    public function getRate(): string
    {
        return $this->rate;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }
}
