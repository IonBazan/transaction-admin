<?php

declare(strict_types=1);

namespace App\Transaction;

use MyCLabs\Enum\Enum;

/**
 * @method static Status COMPLETED()
 * @method static Status REFUNDED()
 * @method static Status CANCELED()
 */
class Status extends Enum
{
    public const COMPLETED = 'completed';
    public const REFUNDED = 'refunded';
    public const CANCELED = 'canceled';
}
