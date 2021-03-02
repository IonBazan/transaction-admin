<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Tests\DatabaseTest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SecurityControllerTest extends DatabaseTest
{
    public function testItRedirectsToLoginForRestrictedPaths(): void
    {
        $this->client->request('GET', '/transactions/list');
        $loginUrl = self::$container->get(UrlGeneratorInterface::class)->generate('login', [], UrlGeneratorInterface::ABSOLUTE_URL);
        self::assertResponseRedirects($loginUrl);
        $this->client->followRedirect();
        self::assertSelectorTextContains('h1', 'Please sign in');
    }

    public function testUserCantLoginWithInvalidPassword(): void
    {
        $this->client->followRedirects();
        $this->client->request(Request::METHOD_GET, 'login');
        $this->client->submitForm('Sign in', [
            '_username' => static::TEST_USERNAME,
            '_password' => 'test',
        ]);
        self::assertSelectorTextContains('div', 'Invalid credentials.');
    }

    public function testUserCanLoginWithValidPasswordAndLogout(): void
    {
        $this->client->followRedirects();
        $this->client->request(Request::METHOD_GET, 'login');
        $this->client->submitForm('Sign in', [
            '_username' => static::TEST_USERNAME,
            '_password' => static::TEST_PASSWORD,
        ]);
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Transaction dashboard');
        $this->client->clickLink('Logout');
        self::assertSelectorTextContains('h1', 'Please sign in');
    }
}
