<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Currency;
use App\Entity\CurrencyPairExchange;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CurrencyPairExchange|null find($id, $lockMode = null, $lockVersion = null)
 * @method CurrencyPairExchange|null findOneBy(array $criteria, array $orderBy = null)
 * @method CurrencyPairExchange[]    findAll()
 * @method CurrencyPairExchange[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CurrencyPairExchangeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CurrencyPairExchange::class);
    }

    public function save(CurrencyPairExchange $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CurrencyPairExchange $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getRatesByTimeData(Currency $base, Currency $quote, DateTimeInterface $from, DateTimeInterface $to) {
        $queryBuilder = $this->createQueryBuilder('exchange');

        $queryBuilder
            ->where($queryBuilder->expr()->between('exchange.createdAt', ':from', ':to'))
            ->andWhere('exchange.base = :base')
            ->andWhere('exchange.quote = :quote')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->setParameter('base', $base)
            ->setParameter('quote', $quote);

        return $queryBuilder->getQuery()->getResult();
    }
}
