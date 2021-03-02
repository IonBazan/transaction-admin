<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Document\Money;
use App\Tests\DatabaseTest;
use App\Transaction\Status;
use Symfony\Component\HttpFoundation\Request;

class DashboardControllerTest extends DatabaseTest
{
    public function testItShowsSummary(): void
    {
        $this->prepareData();
        $this->logIn();
        $this->client->request(Request::METHOD_GET, '/');
        self::assertResponseIsSuccessful();
        self::assertSelectorTextSame('#transaction_count h3', '2');
        self::assertSelectorTextSame('#company_count h3', '2');
    }
}
