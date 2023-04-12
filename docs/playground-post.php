<?php

include("../vendor/autoload.php");
include('../src/Client.php');
use RunCloudIO\PHPApiSDK\Client;

header('Content-Type: application/json; charset=utf-8');
$body = file_get_contents('php://input');
$data = json_decode($body, true);

$client = new Client();

try {
    $response = $client->setToken($data['token'])
        ->send($data['section'], $data['action_name'], $data['url_params'], $data['form_data']);
    http_response_code($response->status);
    echo json_encode($response->data);
} catch (GuzzleHttp\Exception\ClientException $e) {
    http_response_code($e->getResponse()->getStatusCode());
    echo $e->getResponse()->getBody()->getContents();
} catch (Exception $e) {
    http_response_code($e->getCode() ?? 500);
    echo json_encode([
        'message'   => $e->getMessage()
    ]);
}




