<?php

namespace Acme\ApiBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TokenTest extends WebTestCase
{
    public function testAuthenticated()
    {
        $data = array(
            'username' => 'user1',
            'password' => '1234',
        );

        $client = static::createClient();
        $client->request('POST', '/api/tokens', array(), array(), array(), json_encode($data));

        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $result = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('token', $result);
    }

    public function testNoAuthentication()
    {
        $client = static::createClient();
        $client->request('POST', '/api/tokens');

        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testBadCredentials()
    {
        $data = array(
            'username' => 'user1',
            'password' => 'abcd',
        );

        $client = static::createClient();
        $client->request('POST', '/api/tokens', array(), array(), array(), json_encode($data));

        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }
}
