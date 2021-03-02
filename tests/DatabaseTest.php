<?php

declare(strict_types=1);

namespace App\Tests;

use App\Document\Address;
use App\Document\Company;
use App\Document\Money;
use App\Document\Transaction;
use App\Transaction\Status;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\DataFixtures\Purger\MongoDBPurger;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class DatabaseTest extends WebTestCase
{
    protected const TEST_USERNAME = 'test_admin';
    protected const TEST_PASSWORD = 'passw0rd';
    protected DocumentManager $dm;
    protected KernelBrowser $client;

    /**
     * @var Company[]
     */
    protected array $companies;

    /**
     * @var Transaction[]
     */
    protected array $transactions;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->dm = self::$container->get(DocumentManager::class);
        $purger = new MongoDBPurger($this->dm);
        $purger->purge();
    }

    public function createCompany(string $name, ?DateTimeInterface $createdAt = null): Company
    {
        $address = new Address();
        $address->setCity('Singapore');
        $address->setCountry('SG');
        $address->setZip('123456');
        $address->setStreet('Foo Street');
        $address->setPhoneNumber('1234 5678');

        $company = new Company();
        $company->setName($name);
        $company->setAddress($address);
        $company->setTaxNumber('1234');
        $company->setCreatedAt($createdAt ?? new DateTime());
        $this->dm->persist($company);
        $this->dm->flush();

        return $company;
    }

    public function createTransaction(Money $amount, Company $company, Status $status, ?DateTimeInterface $createdAt = null): Transaction
    {
        $transaction = new Transaction();
        $transaction->setAmount($amount);
        $transaction->setCompany($company);
        $transaction->setStatus($status);
        $transaction->setCreatedAt($createdAt ?? new DateTime());
        $this->dm->persist($transaction);
        $this->dm->flush();

        return $transaction;
    }

    protected function logIn(): void
    {
        $this->client->setServerParameters(['PHP_AUTH_USER' => static::TEST_USERNAME, 'PHP_AUTH_PW' => static::TEST_PASSWORD]);
    }

    protected function prepareData(): void
    {
        $company1 = $this->createCompany('test 1');
        $company2 = $this->createCompany('test 2');
        $tr1 = $this->createTransaction(new Money(12.30, 'SGD'), $company1, Status::COMPLETED());
        $tr2 = $this->createTransaction(new Money(10.00, 'SGD'), $company2, Status::COMPLETED());
        $tr3 = $this->createTransaction(new Money(12.00, 'USD'), $company1, Status::CANCELED(), new DateTime('1 year ago'));

        $this->companies = [$company1, $company2];
        $this->transactions = [$tr1, $tr2, $tr3];
    }
}
