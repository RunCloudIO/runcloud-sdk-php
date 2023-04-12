<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use RunCloudIO\PHPApiSDK\Client;

class AgencyClientTest extends TestCase
{
    public $client = null;

    protected function setUp(): void
    {
        $this->client = new Client();
    }

    /**
     * @dataProvider endpoints
     */
    public function testEndpointsValid($expectedOutput, array $input)
    {
        $this->expectExceptionCode(401);

        $this->client->send('agency-api', $input[0], $input[1], []);
    }

    public function endpoints()
    {
        $endpoints = [];

        $client = new Client();

        foreach ($client->getAgencyApi() as $key => $api) {
            $description = sprintf('Route [%s]: %s %s is valid', $key, $api[0], $api[1]);
            $endpoints[$description][0] = null;
            $dummyParams = [];
            $paramsCount = substr_count($api[1], '%s');

            for ($i = 0; $i < $paramsCount; $i++) {
                array_push($dummyParams, $i + 1);
            }
            $endpoints[$description][1] = [$key, $dummyParams];
        }
        
        return $endpoints;
    }
}
