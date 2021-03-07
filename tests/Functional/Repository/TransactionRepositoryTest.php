<?php

declare(strict_types=1);

namespace App\Tests\Functional\Repository;

use App\Document\Money;
use App\Filters\TransactionFilters;
use App\Repository\TransactionRepository;
use App\Tests\Functional\DatabaseTest;
use App\Transaction\Status;

class TransactionRepositoryTest extends DatabaseTest
{
    protected TransactionRepository $repository;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = self::$container->get(TransactionRepository::class);
    }

    public function testGetFilteredTransactions(): void
    {
        $company1 = $this->createCompany('Test 1');
        $company2 = $this->createCompany('Test 2');

        $tr1 = $this->createTransaction(new Money(123, 'SGD'), $company1, Status::COMPLETED());
        $tr2 = $this->createTransaction(new Money(10, 'SGD'), $company2, Status::COMPLETED());
        $tr3 = $this->createTransaction(new Money(10, 'SGD'), $company1, Status::COMPLETED(), new \DateTime('2 days ago'));

        self::assertEquals([$tr1, $tr2, $tr3], $this->repository->getFiltered(new TransactionFilters())->toArray());
        self::assertEquals([$tr1, $tr2], $this->repository->getFiltered(new TransactionFilters(new \DateTime('1 days ago')))->toArray());
        self::assertEquals([$tr2], $this->repository->getFiltered(new TransactionFilters(new \DateTime('1 days ago'), null, [$company2]))->toArray());
        self::assertEquals([$tr2], $this->repository->getFiltered(new TransactionFilters(new \DateTime('1 days ago'), null, [$company2]))->toArray());
    }
}
