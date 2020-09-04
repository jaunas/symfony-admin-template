<?php

namespace App\Tests\Repository;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;

class UserRepositoryTest extends WebTestCase
{
    public function testUpgradePasswordWrongArgument(): void
    {
        self::bootKernel();

        $wrongUser = $this->createMock(UserInterface::class);
        $repository = static::$container->get(UserRepository::class);

        $this->expectException(UnsupportedUserException::class);
        $repository->upgradePassword($wrongUser, 'new_password');
    }
}
