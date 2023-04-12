<?php

namespace RunCloudIO\PHPApiSDK;

use Exception;
use GuzzleHttp\Exception\ClientException;
use RunCloudIO\PHPApiSDK\Exceptions\InvalidParametersException;
use stdClass;

class Client
{
    private $token = '';

    public function __construct()
    {
    }

    public function setToken(string $token)
    {
        $this->token = $token;

        return $this;
    }

    private function getBaseUrl(): string
    {
        return $this->config()['domain'] . $this->config()['base'];
    }

    private function config(): array
    {
        return include('config.php');
    }

    public function send(string $section, string $actionKey, array $urlParams, array $formData)
    {
        return $this->sendRequest($section, $actionKey, $urlParams, $formData);
    }

    private function sendRequest(string $section, string $actionKey, array $urlParams, array $formData)
    {
        $method = $this->config()[$section][$actionKey][0];

        $url = "";

        if ($count = substr_count($this->config()[$section][$actionKey][1], '%s') > 0) {
            if (
                empty($urlParams) ||
                $count = substr_count($this->config()[$section][$actionKey][1], '%s') !== count($urlParams)
                ) {
                throw new InvalidParametersException(
                    'Expecting ' . $count . ' parameter(s) in action name (`' . $actionKey .'`): [' . $method . ']: ' . $this->config()[$section][$actionKey][1],
                    400
                );
            } else {
                $url = vsprintf($this->getBaseUrl() . $this->config()[$section][$actionKey][1], $urlParams);
            }
        } else {
            $url = $this->getBaseUrl() . $this->config()[$section][$actionKey][1];
        }

        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->request($method, $url, [
                'headers'       => [
                    'Accept'    => 'application/json',
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'Bearer ' . $this->token,

                ],
                'json'   => $formData
            ]);

            $responseObj = new stdClass();

            $responseObj->status = $response->getStatusCode();

            foreach ($response->getHeaders() as $name => $values) {
                $responseObj->headers[$name] = implode(', ', $values);
            }

            $responseObj->data = json_decode($response->getBody());

            return $responseObj;

        } catch (ClientException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getAgencyApi()
    {
        return $this->config()['agency-api'];
    }

    public function getCoreApi()
    {
        return $this->config()['core-api'];
    }
}
