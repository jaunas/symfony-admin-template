<?php

namespace App\Tests\Controller;

use Liip\TestFixturesBundle\Test\FixturesTrait;

class SecurityControllerTest extends AuthorizedWebTestCase
{
    use FixturesTrait;

    protected function getURI(): string
    {
        return '/logout';
    }

    public function testLogin(): void
    {
        $crawler = $this->client->request('GET', '/login');
        $this->assertTrue($this->client->getResponse()->isOk());

        $form = $crawler->filter('form[name=login]')->form([
            'login' => [
                'email' => static::USER_EMAIL,
                'password' => 'user'
            ],
        ]);

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect('/'));

        $this->client->request('GET', '/');
        $this->assertTrue($this->client->getResponse()->isOk());
    }

    public function testLoginFail(): void
    {
        $crawler = $this->client->request('GET', '/login');
        $this->assertTrue($this->client->getResponse()->isOk());

        $form = $crawler->filter('form[name=login]')->form([
            'login' => [
                'email' => static::USER_EMAIL,
                'password' => 'pass'
            ],
        ]);

        $this->client->submit($form);
        $this->assertFalse($this->client->getResponse()->isRedirect('/'));

        $this->client->request('GET', '/');
        $this->assertTrue($this->client->getResponse()->isRedirect('/login'));
    }

    public function testLogout(): void
    {
        $user = $this->getUserByEmail(static::USER_EMAIL);
        $this->client->loginUser($user);

        $this->client->request('GET', '/');
        $this->assertTrue($this->client->getResponse()->isOk());

        $this->client->request('GET', '/logout');

        $this->client->request('GET', '/');
        $this->assertTrue($this->client->getResponse()->isRedirect('/login'));
    }
}
