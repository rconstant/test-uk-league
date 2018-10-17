<?php

namespace App\Tests\Controller;

use App\DataFixtures\LeagueFixture;
use App\DataFixtures\TeamFixture;
use App\DataFixtures\UserFixture;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

abstract class AbstractControllerTest extends WebTestCase
{
    const DEFAULT_USER = [
        'username' => 'rconstant',
        'password' => '123'
    ];

    const AUTHORIZATION_KEY = 'HTTP_AUTHORIZATION';

    const MISSING_AUTHORIZATION_HEADER = [
        'error' => 'authentication_failure',
        'error_description' => 'Missing authorization header'
    ];

    const WRONG_NUMBER_SEGMENTS = [
        'error' => 'authentication_failure',
        'error_description' => 'Wrong number of segments'
    ];

    /**
     * @var Client
     */
    protected static $client;
    /**
     * @var string|null
     */
    protected static $accessToken;

    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * AbstractControllerTest constructor.
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function setUp()
    {
        if (!static::$client) {
            static::$client = static::createClient();
            static::$kernel = static::createKernel();
            static::$kernel->boot();
            $this->em = static::$kernel->getContainer()->get('doctrine')->getManager();
            $this->encoder = static::$kernel->getContainer()->get('security.password_encoder');

            $this->database();
            parent::setUp();
        }
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    private function database()
    {
        $loader = new Loader();
        $loader->addFixture(new UserFixture($this->encoder));
        $loader->addFixture(new LeagueFixture());
        $loader->addFixture(new TeamFixture());

        $this->em->getConnection()->executeQuery('SET FOREIGN_KEY_CHECKS = 0');
        $purger = new ORMPurger($this->em);
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
        $executor = new ORMExecutor($this->em, $purger);
        $executor->execute($loader->getFixtures());
        $this->em->getConnection()->executeQuery('SET FOREIGN_KEY_CHECKS = 1');
    }

    protected function getAccessToken()
    {
        if (!self::$accessToken) {
            $response = $this->sendRequest('POST', '/login', self::DEFAULT_USER);
            $this->assertJson($response->getContent());
            $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
            $token = json_decode($response->getContent());
            $this->assertInternalType('string', $token->token);
            self::$accessToken = $token->token;
        }

        return self::$accessToken;
    }

    /**
     * @param string     $method
     * @param string     $path
     * @param array|null $data
     * @param array      $headers
     *
     * @return Response
     */
    protected function sendRequest(string $method, string $path, array $data = null, array $headers = []): Response
    {
        $headers['CONTENT_TYPE'] = 'application/json';

        static::$client->request($method, $path, [], [], $headers, json_encode($data));
        return static::$client->getResponse();
    }

    protected function assertResponse(Response $response, int $code)
    {
        $this->assertEquals($code, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertJson($response->getContent());
    }
}