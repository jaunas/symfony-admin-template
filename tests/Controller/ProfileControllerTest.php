<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfileControllerTest extends AuthorizedWebTestCase
{
    protected function getURI(): string
    {
        return '/profile';
    }

    public function testProfile(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);

        $user = $userRepository->findOneByEmail('user@sat.com');

        $this->assertInstanceOf(UserInterface::class, $user);
        $client->loginUser($user);

        $client->request('GET', $this->getURI());
        $this->assertTrue($client->getResponse()->isOk());

        $this->markTestIncomplete();
    }
}
