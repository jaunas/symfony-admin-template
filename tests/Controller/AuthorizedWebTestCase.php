<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AuthorizedWebTestCase extends WebTestCase
{
    abstract protected function getURI(): string;

    public function testUnauthorized(): void
    {
        $client = static::createClient();
        $client->request('GET', $this->getURI());

        $response = $client->getResponse();

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertTrue($response->isRedirect('/login'));
    }
}
