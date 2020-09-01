<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AuthorizedWebTestCase extends WebTestCase
{
    abstract protected function getURI(): string;

    /** @var KernelBrowser */
    protected $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testUnauthorized(): void
    {
        $this->client->request('GET', $this->getURI());

        $response = $this->client->getResponse();

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertTrue($response->isRedirect('/login'));
    }
}
