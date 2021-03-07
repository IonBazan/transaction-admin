<?php

declare(strict_types=1);

namespace App\Tests\Unit\Doctrine;

use App\Doctrine\StatusType;
use App\Transaction\Status;
use Doctrine\ODM\MongoDB\Types\Type;
use PHPUnit\Framework\TestCase;

class StatusTypeTest extends TestCase
{
    private StatusType $statusType;

    protected function setUp(): void
    {
        Type::registerType(StatusType::class, StatusType::class);
        $this->statusType = Type::getType(StatusType::class);
    }

    public function testItConvertsToDocumentValue(): void
    {
        self::assertSame(Status::CANCELED, $this->statusType->convertToDatabaseValue(Status::CANCELED()));
        self::assertSame(Status::COMPLETED, $this->statusType->convertToDatabaseValue(Status::COMPLETED()));
        self::assertSame(Status::REFUNDED, $this->statusType->convertToDatabaseValue(Status::REFUNDED()));
        self::assertSame(null, $this->statusType->convertToDatabaseValue(null));
    }

    public function testItConvertsToPhpValue(): void
    {
        self::assertEquals(Status::CANCELED(), $this->statusType->convertToPHPValue(Status::CANCELED));
        self::assertEquals(Status::COMPLETED(), $this->statusType->convertToPHPValue(Status::COMPLETED));
        self::assertEquals(Status::REFUNDED(), $this->statusType->convertToPHPValue(Status::REFUNDED));
        self::assertSame(null, $this->statusType->convertToPHPValue(null));
    }
}
