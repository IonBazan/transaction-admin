<?php

declare(strict_types=1);

namespace App\Document;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ODM\Document(repositoryClass="App\Repository\CompanyRepository")
 */
class Company implements \Stringable
{
    /**
     * @ODM\Id(strategy="UUID")
     */
    private string $id;

    /**
     * @ODM\Field()
     * @Assert\NotBlank()
     */
    private string $name;

    /**
     * @ODM\Field()
     * @Assert\NotBlank()
     */
    private string $taxNumber;

    /**
     * @ODM\EmbedOne(targetDocument=Address::class)
     * @Assert\NotBlank()
     * @Assert\Valid();
     */
    private Address $address;

    /**
     * @ODM\ReferenceMany(targetDocument=Transaction::class, mappedBy="company")
     *
     * @var Collection|Transaction[]
     */
    private Collection $transactions;

    /**
     * @ODM\Field(type="date_immutable")
     */
    private DateTimeInterface $createdAt;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->transactions = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getTaxNumber(): string
    {
        return $this->taxNumber;
    }

    public function setTaxNumber(string $taxNumber): void
    {
        $this->taxNumber = $taxNumber;
    }

    /**
     * @return Transaction[]|Collection
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    /**
     * @param Transaction[]|Collection $transactions
     */
    public function setTransactions(Collection $transactions): void
    {
        $this->transactions = $transactions;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): void
    {
        $this->address = $address;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
