<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class DashboardControllerTest extends AuthorizedWebTestCase
{
    protected function getURI(): string
    {
        return '/';
    }

    public function testDashboard(): void
    {
        $userRepository = static::$container->get(UserRepository::class);

        $user = $userRepository->findOneByEmail('user@sat.com');

        $this->assertInstanceOf(UserInterface::class, $user);
        $this->client->loginUser($user);

        $this->client->request('GET', '/');
        $response = $this->client->getResponse();

        $this->assertTrue($response->isOk());
    }
}
