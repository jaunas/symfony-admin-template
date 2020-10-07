<?php

namespace App\Tests\Controller;

use App\Security\LoginFormAuthenticator;

class ProfileControllerTest extends AuthorizedWebTestCase
{
    protected function getURI(): string
    {
        return '/profile';
    }

    public function testProfileChangePasswordNotMatch(): void
    {
        $user = $this->getUserByEmail(static::USER_EMAIL);

        $authenticator = static::$container->get(LoginFormAuthenticator::class);
        $this->assertTrue($authenticator->checkCredentials(['password' => 'user'], $user));

        $this->client->loginUser($user);

        $crawler = $this->client->request('GET', $this->getURI());
        $this->assertTrue($this->client->getResponse()->isOk());

        $form = $crawler->filter('form[name=change_password]')->form([
            'change_password' => [
                'password' => [
                    'first' => 'Pass',
                    'second' => 'pass',
                ],
            ],
        ]);

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isOk());

        $user = $this->getUserByEmail(static::USER_EMAIL);
        $this->assertTrue($authenticator->checkCredentials(['password' => 'user'], $user));
    }

    public function testProfileChangePassword(): void
    {
        $user = $this->getUserByEmail(static::USER_EMAIL);

        $authenticator = static::$container->get(LoginFormAuthenticator::class);
        $this->assertTrue($authenticator->checkCredentials(['password' => 'user'], $user));

        $this->client->loginUser($user);

        $crawler = $this->client->request('GET', $this->getURI());
        $this->assertTrue($this->client->getResponse()->isOk());

        $form = $crawler->filter('form[name=change_password]')->form([
            'change_password' => [
                'password' => [
                    'first' => 'pass',
                    'second' => 'pass',
                ],
            ],
        ]);

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isOk());

        $user = $this->getUserByEmail(static::USER_EMAIL);
        $this->assertTrue($authenticator->checkCredentials(['password' => 'pass'], $user));
    }

    public function testRegister(): void
    {
        $email = 'new.user@sat.com';
        $password = 'pass';

        $this->register($email, $password);
        $this->assertTrue($this->client->getResponse()->isRedirect('/login'));

        $user = $this->getUserByEmail($email);

        $this->assertNotNull($user);

        $authenticator = static::$container->get(LoginFormAuthenticator::class);
        $this->assertTrue($authenticator->checkCredentials(['password' => $password], $user));
    }

    public function testRegisterExistingEmail(): void
    {
        $this->register(static::USER_EMAIL, 'pass');
        $this->assertTrue($this->client->getResponse()->isOk());

        $this->assertSelectorTextContains('form[name=register]', 'This value is already used.');
    }

    public function register(string $email, string $password): void
    {
        $crawler = $this->client->request('GET', '/register');
        $this->assertTrue($this->client->getResponse()->isOk());

        $formData = [
            'register' => [
                'email' => $email,
                'password' => [
                    'first' => $password,
                    'second' => $password,
                ],
            ],
        ];

        $form = $crawler->filter('form[name=register]')->form($formData);
        $this->client->submit($form);
    }

    public function testRegisterAlreadyLoggedIn(): void
    {
        $this->client->loginUser($this->getUserByEmail(static::USER_EMAIL));
        $this->client->request('GET', '/register');

        $this->assertTrue($this->client->getResponse()->isRedirect('/'));
    }
}
