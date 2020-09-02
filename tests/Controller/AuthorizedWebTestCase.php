<?php

namespace App\Tests\Controller;

use App\DataFixtures\UserFixtures;
use App\Entity\User;
use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AuthorizedWebTestCase extends WebTestCase
{
    const USER_EMAIL = 'user@sat.com';

    use FixturesTrait;

    abstract protected function getURI(): string;

    /** @var KernelBrowser */
    protected $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $this->loadFixtures([
            UserFixtures::class,
        ]);
    }

    public function testUnauthorized(): void
    {
        $this->client->request('GET', $this->getURI());

        $response = $this->client->getResponse();

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertTrue($response->isRedirect('/login'));
    }

    protected function getUserByEmail(string $email): User
    {
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByEmail($email);
        $this->assertInstanceOf(User::class, $user);

        return $user;
    }
}
