<?php

declare(strict_types=1);

namespace App\Repository;

use App\Document\Company;
use App\Document\Transaction;
use App\Filters\TransactionFilters;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;
use Doctrine\ODM\MongoDB\Aggregation\Stage\MatchStage;
use Doctrine\ODM\MongoDB\Iterator\Iterator;
use Doctrine\ODM\MongoDB\Query\Builder;

class TransactionRepository extends ServiceDocumentRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    public function getFiltered(TransactionFilters $filters): Iterator
    {
        return $this->applyFilters($this->createQueryBuilder(), $filters)->getQuery()->execute();
    }

    public function getStats(TransactionFilters $filters): array
    {
        return iterator_to_array(
            $this->applyFilters($this->createAggregationBuilder()->match(), $filters)
            ->group()
            ->field('id')
            ->expression('$amount.currency')
            ->field('amount')
            ->sum('$amount.amount')
            ->getAggregation()
            ->getIterator()
        );
    }

    public function getTransactionsCount(\DateTimeInterface $dateFrom, \DateTimeInterface $dateTo): int
    {
        return $this->createQueryBuilder()
            ->field('createdAt')
            ->gte($dateFrom)
            ->lt($dateTo)
            ->count()->getQuery()->execute() ?? 0;
    }

    public function save(Transaction $transaction)
    {
        $this->dm->persist($transaction);
        $this->dm->flush();
    }

    public function remove(Transaction $transaction)
    {
        $this->dm->remove($transaction);
        $this->dm->flush();
    }

    private function applyFilters(Builder | MatchStage $builder, TransactionFilters $filters): Builder | MatchStage
    {
        if ($filters->dateFrom) {
            $builder->field('createdAt')->gte($filters->dateFrom);
        }

        if ($filters->dateTo) {
            $builder->field('createdAt')->lte($filters->dateTo);
        }

        if ($filters->companies) {
            $builder->field('company.$id')->in(array_map(static fn (Company $company) => $company->getId(), $filters->companies));
        }

        return $builder;
    }
}
