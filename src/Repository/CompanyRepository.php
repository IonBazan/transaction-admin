<?php

declare(strict_types=1);

namespace App\Repository;

use App\Document\Company;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;

class CompanyRepository extends ServiceDocumentRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Company::class);
    }

    public function save(Company $company)
    {
        $this->dm->persist($company);
        $this->dm->flush();
    }

    public function getCompaniesCount(\DateTimeInterface $dateFrom, \DateTimeInterface $dateTo): int
    {
        return $this->createQueryBuilder()
            ->field('createdAt')
            ->gte($dateFrom)
            ->lt($dateTo)
            ->count()->getQuery()->execute() ?? 0;
    }
}
