<?php

declare(strict_types=1);

namespace App\Document;

use App\Transaction\Status;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ODM\Document(repositoryClass="App\Repository\TransactionRepository")
 */
class Transaction
{
    /**
     * @ODM\Id(strategy="UUID")
     */
    private string $id;

    /**
     * @ODM\EmbedOne(targetDocument=Money::class)
     * @Assert\NotBlank()
     * @Assert\Valid()
     */
    private Money $amount;
    /**
     * @ODM\ReferenceOne(targetDocument=Company::class, inversedBy="transactions")
     * @Assert\NotBlank()
     */
    private Company $company;

    /**
     * @ODM\Field(type="transaction_status")
     * @Assert\NotBlank()
     */
    private Status $status;

    /**
     * @ODM\Field(type="date_immutable")
     */
    private DateTimeInterface $createdAt;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getAmount(): Money
    {
        return $this->amount;
    }

    public function setAmount(Money $amount): void
    {
        $this->amount = $amount;
    }

    public function getCompany(): Company
    {
        return $this->company;
    }

    public function setCompany(Company $company): void
    {
        $this->company = $company;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): void
    {
        $this->status = $status;
    }
}
