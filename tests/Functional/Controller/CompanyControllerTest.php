<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Tests\DatabaseTest;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;

class CompanyControllerTest extends DatabaseTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->logIn();
        $this->prepareData();
    }

    public function testItShowsCompanyList(): void
    {
        $crawler = $this->client->request(Request::METHOD_GET, '/companies');
        self::assertResponseIsSuccessful();
        self::assertSame(count($this->companies), $crawler->filter('#company_list > tbody > tr')->count());
    }

    public function testItShowsCompany(): void
    {
        $company = $this->companies[0];
        $crawler = $this->client->request(Request::METHOD_GET, sprintf('/companies/%s', $company->getId()));
        self::assertResponseIsSuccessful();
        self::assertSame(
            [
                sprintf('ID %s', $company->getId()),
                sprintf('Name %s', $company->getName()),
                sprintf('Tax number %s', $company->getTaxNumber()),
                sprintf('Street %s', $company->getAddress()->getStreet()),
                sprintf('ZIP %s', $company->getAddress()->getZip()),
                sprintf('City %s', $company->getAddress()->getCity()),
                sprintf('Country %s', $company->getAddress()->getCountry()),
                sprintf('Phone number %s', $company->getAddress()->getPhoneNumber()),
            ],
            $crawler->filter('.row')->each(static fn (Crawler $node) => $node->text())
        );
    }

    public function testItFailsWithEmptyData(): void
    {
        $this->client->followRedirects();
        $this->client->request(Request::METHOD_GET, '/companies/create');
        $crawler = $this->client->submitForm('Submit');
        self::assertResponseIsSuccessful();
        self::assertStringContainsString('is-invalid', $crawler->filter('#company_name')->attr('class'));
        self::assertStringContainsString('is-invalid', $crawler->filter('#company_taxNumber')->attr('class'));
        self::assertStringContainsString('is-invalid', $crawler->filter('#company_address_street')->attr('class'));
        self::assertStringContainsString('is-invalid', $crawler->filter('#company_address_city')->attr('class'));
        self::assertStringContainsString('is-invalid', $crawler->filter('#company_address_zip')->attr('class'));
        self::assertStringContainsString('is-invalid', $crawler->filter('#company_address_phoneNumber')->attr('class'));
    }

    public function testItCreatesNewCompany(): void
    {
        $this->client->followRedirects();
        $this->client->request(Request::METHOD_GET, '/companies/create');
        $crawler = $this->client->submitForm(
            'Submit',
            [
                'company[name]' => 'New Company',
                'company[taxNumber]' => 'TEST123',
                'company[address][street]' => 'Bar Street 123',
                'company[address][city]' => 'Downtown',
                'company[address][zip]' => '888888',
                'company[address][country]' => 'PL',
                'company[address][phoneNumber]' => '1234 1234',
            ]
        );
        self::assertResponseIsSuccessful();
        self::assertStringContainsString(
            'New Company TEST123 Bar Street 123 Downtown 888888 PL 1234 1234',
            $crawler->filter('#company_list > tbody tr')->eq(2)->text()
        );
    }

    public function testItEditsCompany(): void
    {
        $company = $this->companies[0];
        $this->client->followRedirects();
        $this->client->request(Request::METHOD_GET, sprintf('/companies/edit/%s', $company->getId()));
        self::assertResponseIsSuccessful();
        $crawler = $this->client->submitForm(
            'Submit',
            [
                'company[name]' => 'New Company',
                'company[taxNumber]' => 'TEST123',
                'company[address][street]' => 'Bar Street 123',
                'company[address][city]' => 'Downtown',
                'company[address][zip]' => '888888',
                'company[address][country]' => 'PL',
                'company[address][phoneNumber]' => '1234 1234',
            ]
        );
        self::assertStringContainsString(
            'New Company TEST123 Bar Street 123 Downtown 888888 PL 1234 1234',
            $crawler->filter('#company_list > tbody tr')->eq(0)->text()
        );
    }
}
