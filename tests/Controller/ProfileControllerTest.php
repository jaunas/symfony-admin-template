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
}
