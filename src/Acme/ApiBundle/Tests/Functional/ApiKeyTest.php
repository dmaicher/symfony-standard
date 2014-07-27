<?php

namespace Acme\ApiBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyTest extends WebTestCase
{
    public function testAuthenticated()
    {
        $client = static::createClient();
        $client->request('GET', '/api/hello', array('apikey' => '123abc'));

        $result = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(array('hello' => 'world'), $result);
    }

    public function testNoAuthentication()
    {
        $client = static::createClient();
        $client->request('GET', '/api/hello');

        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testBadCredentials()
    {
        $client = static::createClient();
        $client->request('GET', '/api/hello', array('apikey' => '123'));

        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testNonAuthorized()
    {
        $client = static::createClient();
        $client->request('GET', '/api/hello', array('apikey' => 'def456'));

        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}
