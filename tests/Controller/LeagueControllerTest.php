<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LeagueControllerTest extends WebTestCase
{
    public function testGetTeamsReturn200()
    {
        $client = static::createClient();
        $client->request('GET', 'leagues/1/teams');

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertJson($response->getContent());
    }

    public function testGetTeamsReturn404()
    {
        $client = static::createClient();
        $client->request('GET', 'leagues/99/teams');

        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertJson($response->getContent());

        $data = [
            'code' => 404,
            'message' => 'League not found.'
        ];

        $this->assertSame($data, json_decode($response->getContent(), true));
    }

    public function testDeleteLeague()
    {
        $client = static::createClient();
        $client->request('DELETE', 'leagues/5');

        $response = $client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testDeleteLeagueReturn404()
    {
        $client = static::createClient();
        $client->request('DELETE', 'leagues/99');

        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertJson($response->getContent());

        $data = [
            'code' => 404,
            'message' => 'League not found.'
        ];

        $this->assertSame($data, json_decode($response->getContent(), true));
    }
}