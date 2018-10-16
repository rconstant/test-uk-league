<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TeamControllerTest extends WebTestCase
{
    public function testCreateTeamValid()
    {
        $client = static::createClient();
        $data = [
            'name' => 'Test',
            'strip' => 'red',
            'league' => 1
        ];
        $client->request('POST', 'teams', [], [], ['Content-Type' => 'application/json'], json_encode($data));
        $response = $client->getResponse();

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertJson($response->getContent());
    }

    public function testCreateTeamInvalid()
    {
        $client = static::createClient();
        $data = [
            'name' => 'Test',
            'strip' => 'pink',
            'league' => 1
        ];
        $client->request('POST', 'teams', [], [], ['Content-Type' => 'application/json'], json_encode($data));

        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertJson($response->getContent());
    }

    public function testUpdateTeamValid()
    {
        $client = static::createClient();
        $data = [
            'name' => 'Test 2',
            'strip' => 'green',
            'league' => 2
        ];
        $client->request('PUT', 'teams/1', [], [], ['Content-Type' => 'application/json'], json_encode($data));
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertJson($response->getContent());
    }

    public function testUpdateTeamInvalid()
    {
        $client = static::createClient();
        $data = [
            'name' => 'Test 2',
            'strip' => 'green',
            'league' => 99
        ];
        $client->request('PUT', 'teams/1', [], [], ['Content-Type' => 'application/json'], json_encode($data));
        $response = $client->getResponse();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertJson($response->getContent());
    }
}