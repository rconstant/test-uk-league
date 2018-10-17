<?php

namespace App\Tests\Controller;

class LeagueControllerTest extends AbstractControllerTest
{
    private const URL_LEAGUE_EXIST_GET_TEAMS = '/api/leagues/1/teams';
    private const URL_LEAGUE_NOT_EXIST_GET_TEAMS = '/api/leagues/99/teams';
    private const URL_LEAGUE_EXIST_DELETE = '/api/leagues/5';
    private const URL_LEAGUE_NOT_EXIST_DELETE = '/api/leagues/99';

    public function testGetTeamsUnauthorized()
    {
        $response = parent::sendRequest('GET', self::URL_LEAGUE_EXIST_GET_TEAMS);

        parent::assertResponse($response, 401);
        $this->assertEquals(json_decode($response->getContent(), true), parent::MISSING_AUTHORIZATION_HEADER);
    }

    public function testGetTeamsBadToken()
    {
        $response = parent::sendRequest('GET', self::URL_LEAGUE_EXIST_GET_TEAMS, [], [
            parent::AUTHORIZATION_KEY => 'Bearer 123'
        ]);

        parent::assertResponse($response, 401);
        $this->assertEquals(json_decode($response->getContent(), true), parent::WRONG_NUMBER_SEGMENTS);
    }

    public function testGetTeamsReturn200()
    {
        $response = parent::sendRequest('GET', self::URL_LEAGUE_EXIST_GET_TEAMS, [], [
            parent::AUTHORIZATION_KEY => 'Bearer ' . parent::getAccessToken()
        ]);

        parent::assertResponse($response, 200);
    }

    public function testGetTeamsReturn404()
    {
        $response = parent::sendRequest('GET', self::URL_LEAGUE_NOT_EXIST_GET_TEAMS, [], [
            parent::AUTHORIZATION_KEY => 'Bearer ' . parent::getAccessToken()
        ]);

        parent::assertResponse($response, 404);

        $data = [
            'code' => 404,
            'message' => 'League not found.'
        ];

        $this->assertSame($data, json_decode($response->getContent(), true));
    }

    public function testDeleteLeagueUnauthorized()
    {
        $response = parent::sendRequest('DELETE', self::URL_LEAGUE_EXIST_DELETE, []);

        parent::assertResponse($response, 401);
        $this->assertEquals(json_decode($response->getContent(), true), parent::MISSING_AUTHORIZATION_HEADER);
    }

    public function testDeleteLeagueBadToken()
    {
        $response = parent::sendRequest('DELETE', self::URL_LEAGUE_EXIST_DELETE, [], [
            parent::AUTHORIZATION_KEY => 'Bearer 123'
        ]);

        parent::assertResponse($response, 401);
        $this->assertEquals(json_decode($response->getContent(), true), parent::WRONG_NUMBER_SEGMENTS);
    }

    public function testDeleteLeagueReturn204()
    {
        $response = parent::sendRequest('DELETE', self::URL_LEAGUE_EXIST_DELETE, [], [
            parent::AUTHORIZATION_KEY => 'Bearer ' . parent::getAccessToken()
        ]);

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertEmpty($response->getContent());

    }

    public function testDeleteLeagueReturn404()
    {
        $response = parent::sendRequest('DELETE', self::URL_LEAGUE_NOT_EXIST_DELETE, [], [
            parent::AUTHORIZATION_KEY => 'Bearer ' . parent::getAccessToken()
        ]);

        parent::assertResponse($response, 404);

        $data = [
            'code' => 404,
            'message' => 'League not found.'
        ];

        $this->assertSame($data, json_decode($response->getContent(), true));
    }
}