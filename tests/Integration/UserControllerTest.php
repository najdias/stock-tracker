<?php

namespace App\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{
    public function testCreatesUserSuccessfully(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/register',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['email' => 'test@example.com', 'password' => 'password123'])
        );

        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['message' => 'User created!']),
            $client->getResponse()->getContent()
        );
    }

    public function testFailsWithInvalidJson(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/register',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            'invalid-json'
        );

        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $client->getResponse()->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['message' => 'Unable to create user']),
            $client->getResponse()->getContent()
        );
    }

    public function testFailsWithMissingFields(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/register',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['email' => 'test@example.com'])
        );

        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $client->getResponse()->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['message' => 'Unable to create user']),
            $client->getResponse()->getContent()
        );
    }
}
