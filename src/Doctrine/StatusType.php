<?php

declare(strict_types=1);

namespace App\Doctrine;

use App\Transaction\Status;
use Doctrine\ODM\MongoDB\Types\ClosureToPHP;
use Doctrine\ODM\MongoDB\Types\Type;

class StatusType extends Type
{
    use ClosureToPHP;

    public function convertToDatabaseValue($value)
    {
        return null === $value ? null : $value->getValue();
    }

    public function convertToPHPValue($value)
    {
        return null !== $value ? Status::from($value) : null;
    }
}
