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
        $this->markTestIncomplete();
    }

    public function testLogout(): void
    {
        $this->markTestIncomplete();
    }
}
