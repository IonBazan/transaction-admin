<?php

declare(strict_types=1);

namespace App\Filters;

use App\Document\Company;
use DateTimeInterface;

class TransactionFilters
{
    public ?DateTimeInterface $dateFrom = null;
    public ?DateTimeInterface $dateTo = null;
    /**
     * @var Company[]|array
     */
    public array $companies = [];

    public function __construct(?DateTimeInterface $dateFrom = null, ?DateTimeInterface $dateTo = null, array $companies = [])
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->companies = $companies;
    }
}
