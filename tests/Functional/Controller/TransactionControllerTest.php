<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Document\Transaction;
use App\Tests\Functional\DatabaseTest;
use App\Transaction\Status;
use DateTime;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;

class TransactionControllerTest extends DatabaseTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->logIn();
        $this->prepareData();
    }

    public function testItShowsTransactionList(): void
    {
        $crawler = $this->client->request(Request::METHOD_GET, '/transactions/list');
        self::assertResponseIsSuccessful();
        self::assertSame(3, $crawler->filter('#transaction_list > tbody > tr')->count());
    }

    public function testItFiltersList(): void
    {
        $this->client->request(Request::METHOD_GET, '/transactions/list');
        $crawler = $this->client->submitForm(
            'Submit',
            [
                'transaction_filters[dateFrom]' => (new DateTime('yesterday'))->format('Y-m-d\TH:i'),
                'transaction_filters[dateTo]' => (new DateTime('tomorrow'))->format('Y-m-d\TH:i'),
                'transaction_filters[companies]' => [$this->companies[0]->getId()],
            ]
        );
        self::assertResponseIsSuccessful();
        $tableCrawler = $crawler->filter('#transaction_list > tbody > tr');
        self::assertContainsTransactions([$this->transactions[0]], $tableCrawler);
        self::assertSelectorTextSame('#transaction_summary', 'Currency Total amount SGD 12.30');
    }

    public function testItShowsTransaction(): void
    {
        $transaction = $this->transactions[0];
        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/transactions/%s', $transaction->getId()));
        self::assertResponseIsSuccessful();
        self::assertSame([
            sprintf('ID %s', $transaction->getId()),
            sprintf('Company %s', $transaction->getCompany()->getName()),
            sprintf('Amount %.2f %s', $transaction->getAmount()->getAmount(), $transaction->getAmount()->getCurrency()),
            sprintf('Status %s', $transaction->getStatus()),
            sprintf('Created At %s', $transaction->getCreatedAt()->format('Y-m-d H:i:s')),
        ], $crawler->filter('.row')->each(static fn (Crawler $node) => $node->text()));
    }

    public function testItCreatesNewTransaction(): void
    {
        $this->client->followRedirects();
        $this->client->request(Request::METHOD_GET, '/transactions/create');
        self::assertResponseIsSuccessful();
        $crawler = $this->client->submitForm('Submit', ['transaction[amount][amount]' => 999.12]);
        self::assertSelectorTextContains('#transaction_list > tbody', '999.12');
    }

    public function testEditTransaction(): void
    {
        $this->client->followRedirects();
        $this->client->request(Request::METHOD_GET, sprintf('/transactions/edit/%s', $this->transactions[0]->getId()));
        self::assertResponseIsSuccessful();
        $this->client->submitForm('Submit', [
            'transaction[amount][amount]' => 12.12,
            'transaction[amount][currency]' => 'USD',
            'transaction[company]' => $this->companies[0]->getId(),
            'transaction[status]' => Status::CANCELED(),
        ]);
        self::assertSelectorTextContains('#transaction_list > tbody > tr', '12.12');
        self::assertSelectorTextContains('#transaction_list > tbody > tr', 'USD');
        self::assertSelectorTextContains('#transaction_list > tbody > tr', 'test 1');
    }

    public function testDeleteTransaction(): void
    {
        $this->client->followRedirects();
        $this->client->request(Request::METHOD_GET, sprintf('/transactions/delete/%s', $this->transactions[0]->getId()));
        self::assertResponseIsSuccessful();
        $this->client->submitForm('Delete');
        self::assertSelectorTextNotContains('#transaction_list', $this->transactions[0]->getId());
        self::assertSelectorTextContains('.alert', $this->transactions[0]->getId());
    }

    public function testCancelDeleteTransaction(): void
    {
        $this->client->followRedirects();
        $this->client->request(Request::METHOD_GET, sprintf('/transactions/delete/%s', $this->transactions[0]->getId()));
        self::assertResponseIsSuccessful();
        $this->client->clickLink('Cancel');
        self::assertSelectorTextContains('#transaction_list', $this->transactions[0]->getId());
        self::assertSelectorNotExists('.alert');
    }

    private static function assertContainsTransactions(array $transactions, Crawler $tableCrawler): void
    {
        self::assertSame(
            array_map(static fn (Transaction $transaction) => $transaction->getId(), $transactions),
            $tableCrawler->each(static fn (Crawler $node) => $node->filter('td')->first()->text())
        );
    }
}
