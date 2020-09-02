<?php

namespace App\Tests\Controller;

class DashboardControllerTest extends AuthorizedWebTestCase
{
    protected function getURI(): string
    {
        return '/';
    }

    public function testDashboard(): void
    {
        $user = $this->getUserByEmail(static::USER_EMAIL);
        $this->client->loginUser($user);

        $this->client->request('GET', '/');
        $response = $this->client->getResponse();

        $this->assertTrue($response->isOk());
    }
}
