<?php

declare(strict_types=1);

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ODM\EmbeddedDocument()
 */
class Address
{
    /**
     * @ODM\Field()
     * @Assert\NotBlank()
     */
    private string $street;

    /**
     * @ODM\Field()
     * @Assert\NotBlank()
     */
    private string $zip;

    /**
     * @ODM\Field()
     * @Assert\NotBlank()
     */
    private string $phoneNumber;

    /**
     * @ODM\Field()
     * @Assert\NotBlank()
     */
    private string $city;

    /**
     * @ODM\Field()
     * @Assert\NotBlank()
     */
    private string $country;

    public function getStreet(): string
    {
        return $this->street;
    }

    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    public function getZip(): string
    {
        return $this->zip;
    }

    public function setZip(string $zip): void
    {
        $this->zip = $zip;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): void
    {
        $this->country = $country;
    }
}
