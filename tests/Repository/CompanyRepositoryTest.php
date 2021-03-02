<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\Repository\CompanyRepository;
use App\Tests\DatabaseTest;

class CompanyRepositoryTest extends DatabaseTest
{
    protected CompanyRepository $repository;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = self::$container->get(CompanyRepository::class);
    }

    public function testGetCompaniesCount(): void
    {
        $this->createCompany('New 1');
        $this->createCompany('New 2');
        $this->createCompany('Old', new \DateTime('2 days ago'));
        self::assertSame(2, $this->repository->getCompaniesCount(new \DateTime('1 day ago'), new \DateTime()));
    }
}
