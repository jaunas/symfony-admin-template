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
                'password' => 'user',
            ],
        ]);

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect('/'));

        $this->client->request('GET', '/');
        $this->assertTrue($this->client->getResponse()->isOk());
    }

    public function testLoginRedirect(): void
    {
        $this->client->request('GET', '/profile');
        $this->assertTrue($this->client->getResponse()->isRedirect('/login'));
        $this->client->followRedirect();

        $crawler = $this->client->request('GET', '/login');
        $this->assertTrue($this->client->getResponse()->isOk());

        $form = $crawler->filter('form[name=login]')->form([
            'login' => [
                'email' => static::USER_EMAIL,
                'password' => 'user',
            ],
        ]);

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect('http://localhost/profile'));
    }

    /**
     * @dataProvider loginInvalidCredentialsProvider
     *
     * @param array<string> $credentials
     */
    public function testLoginInvalidCredentials(array $credentials, string $errorMessage): void
    {
        $crawler = $this->client->request('GET', '/login');
        $this->assertTrue($this->client->getResponse()->isOk());

        $form = $crawler->filter('form[name=login]')->form(['login' => $credentials]);

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect('/login'));
        $this->client->followRedirect();

        $this->assertSelectorTextContains('form[name=login]', $errorMessage);

        $this->client->request('GET', '/');
        $this->assertTrue($this->client->getResponse()->isRedirect('/login'));
    }

    /**
     * @return array[]
     */
    public function loginInvalidCredentialsProvider(): array
    {
        return [
            [
                'credentials' => [
                    'email' => static::USER_EMAIL,
                    'password' => 'pass',
                ],
                'Invalid credentials.',
            ],
            [
                'credentials' => [
                    'email' => 'nonexisting.user@sat.com',
                    'password' => 'pass',
                ],
                'Email could not be found.',
            ],
            [
                'credentials' => [
                    'email' => static::USER_EMAIL,
                    'password' => 'user',
                    'csrf_token' => 'invalid_token',
                ],
                'Invalid CSRF token.',
            ],
        ];
    }

    public function testAlreadyLoggedIn(): void
    {
        $this->client->loginUser($this->getUserByEmail(static::USER_EMAIL));
        $this->client->request('GET', '/login');

        $this->assertTrue($this->client->getResponse()->isRedirect('/'));
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
