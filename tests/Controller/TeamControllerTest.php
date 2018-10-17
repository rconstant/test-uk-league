<?php

namespace App\Tests\Controller;

class TeamControllerTest extends AbstractControllerTest
{
    private const URL_TEAM_CREATE = '/api/teams';
    private const URL_TEAM_UPDATE = '/api/teams/1';

    private const DATA_CREATE_VALID_TEAM = [
        'name' => 'Test',
        'strip' => 'red',
        'league' => 1
    ];

    private const DATA_CREATE_INVALID_TEAM = [
        'name' => 'Test',
        'strip' => 'pink',
        'league' => 1
    ];

    private const DATA_UPDATE_VALID_TEAM = [
        'name' => 'Test 2',
        'strip' => 'green',
        'league' => 2
    ];

    private const DATA_UPDATE_INVALID_TEAM = [
        'name' => 'Test',
        'strip' => 'pink',
        'league' => 99
    ];

    public function testCreateTeamUnauthorized()
    {
        $response = parent::sendRequest('POST', self::URL_TEAM_CREATE, self::DATA_CREATE_VALID_TEAM);
        parent::assertResponse($response, 401);
        $this->assertEquals(json_decode($response->getContent(), true), parent::MISSING_AUTHORIZATION_HEADER);
    }

    public function testCreateTeamBadToken()
    {
        $response = parent::sendRequest('POST', self::URL_TEAM_CREATE, self::DATA_CREATE_VALID_TEAM, [
            parent::AUTHORIZATION_KEY => 'Bearer 123'
        ]);
        parent::assertResponse($response, 401);
        $this->assertEquals(json_decode($response->getContent(), true), parent::WRONG_NUMBER_SEGMENTS);
    }

    public function testCreateTeamReturn201()
    {
        $response = parent::sendRequest('POST', self::URL_TEAM_CREATE, self::DATA_CREATE_VALID_TEAM, [
            parent::AUTHORIZATION_KEY => 'Bearer ' . parent::getAccessToken()
        ]);

        parent::assertResponse($response, 201);
    }

    public function testCreateTeamReturn400()
    {
        $response = parent::sendRequest('POST', self::URL_TEAM_CREATE, self::DATA_CREATE_INVALID_TEAM, [
            parent::AUTHORIZATION_KEY => 'Bearer ' . parent::getAccessToken()
        ]);

        parent::assertResponse($response, 400);
    }

    public function testUpdateTeamUnauthorized()
    {
        $response = parent::sendRequest('PUT', self::URL_TEAM_UPDATE, self::DATA_UPDATE_VALID_TEAM);
        parent::assertResponse($response, 401);
        $this->assertEquals(json_decode($response->getContent(), true), parent::MISSING_AUTHORIZATION_HEADER);
    }

    public function testUpdateTeamBadToken()
    {
       $response = parent::sendRequest('PUT', self::URL_TEAM_UPDATE, self::DATA_UPDATE_VALID_TEAM, [
            parent::AUTHORIZATION_KEY => 'Bearer 123'
        ]);
        parent::assertResponse($response, 401);
        $this->assertEquals(json_decode($response->getContent(), true), parent::WRONG_NUMBER_SEGMENTS);
    }

    public function testUpdateTeamReturn200()
    {
        $response = parent::sendRequest('PUT', self::URL_TEAM_UPDATE, self::DATA_UPDATE_VALID_TEAM, [
            parent::AUTHORIZATION_KEY => 'Bearer ' . parent::getAccessToken()
        ]);

        parent::assertResponse($response, 200);
    }

    public function testUpdateTeamWithNoDataReturn400()
    {
        $response = parent::sendRequest('PUT', self::URL_TEAM_UPDATE, [], [
            parent::AUTHORIZATION_KEY => 'Bearer ' . parent::getAccessToken()
        ]);
        parent::assertResponse($response, 200);
    }

    public function testUpdateTeamReturn400()
    {
        $response = parent::sendRequest('PUT', self::URL_TEAM_UPDATE, self::DATA_UPDATE_INVALID_TEAM, [
            parent::AUTHORIZATION_KEY => 'Bearer ' . parent::getAccessToken()
        ]);

        parent::assertResponse($response, 400);
    }
}