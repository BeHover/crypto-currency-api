<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CurrencyRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Table(name: "currency")]
#[ORM\Index(columns: ["tag"], name: "idx_currency_tag")]
#[ORM\Entity(repositoryClass: CurrencyRepository::class)]
class Currency
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid")]
    private UuidInterface $id;

    #[ORM\Column(type: "string", length: 180, unique: true)]
    private string $tag;

    #[ORM\Column(type: "string", length: 180)]
    private string $name;

    #[ORM\Column(name: "is_crypto",type: "boolean")]
    private bool $isCrypto;

    public function __construct(string $currencyTag, string $currencyName, bool $isCrypto)
    {
        $this->id = Uuid::uuid6();
        $this->tag = $currencyTag;
        $this->name = $currencyName;
        $this->isCrypto = $isCrypto;
    }

    public function getId(): ?UuidInterface
    {
        return $this->id;
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
}
