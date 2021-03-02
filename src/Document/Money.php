<?php

declare(strict_types=1);

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Stringable;

/**
 * @ODM\EmbeddedDocument()
 */
class Money implements Stringable
{
    /**
     * @ODM\Field(type="float")
     */
    private float $amount;
    /**
     * @ODM\Field()
     */
    private string $currency;

    public function __construct(float $amount, string $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    public function __toString(): string
    {
        return sprintf('%0.2f %s', $this->amount, $this->currency);
    }
}
