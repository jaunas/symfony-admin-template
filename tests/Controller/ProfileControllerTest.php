<?php

namespace App\Tests\Controller;

use App\DataFixtures\UserFixtures;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfileControllerTest extends AuthorizedWebTestCase
{
    use FixturesTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([
            UserFixtures::class,
        ]);
    }

    protected function getURI(): string
    {
        return '/profile';
    }

    public function testProfileChangePasswordNotMatch(): void
    {
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('user@sat.com');
        $this->assertInstanceOf(UserInterface::class, $user);

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
        $this->assertTrue($authenticator->checkCredentials(['password' => 'user'], $user));
    }

    public function testProfileChangePassword(): void
    {
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('user@sat.com');
        $this->assertInstanceOf(UserInterface::class, $user);

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

        $user = $userRepository->findOneByEmail('user@sat.com');
        $this->assertInstanceOf(UserInterface::class, $user);
        $this->assertTrue($authenticator->checkCredentials(['password' => 'pass'], $user));
    }
}
