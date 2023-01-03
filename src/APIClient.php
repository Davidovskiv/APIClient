<?php

namespace App\src;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7;
use App\src\AbstractAPIClient;
use App\auth\Basic;
use App\auth\JWT;

class APIClient extends AbstractAPIClient
{
    public Client $client;
    public array $headers;
    public function __construct()
    {
        parent::__construct();
    }
    public function get(
        string $resource,
        string $id = NULL,
        bool $authBasic = FALSE,
        bool $authJWT = FALSE
    ): array |string
    {
        $path = $id ? $resource . '/' . $id : $resource;
        try {
            $request = new Request('GET', $path, $this->headers);
            $response = $this->client->send($request);
            return json_decode($response->getBody(), true);
        } catch (ClientException $e) {
            return Psr7\Message::toString($e->getResponse());
        }
    }

    public function create(
        string $resource,
        string $id = NULL,
        array $data,
        bool $authBasic = FALSE,
        bool $authJWT = FALSE
    ): int|string
    {
        $data = json_encode($data);
        $path = $id ? $resource . '/' . $id : $resource;
        try {
            $request = new Request('POST', $path, $this->headers, $data);
            $response = $this->client->send($request);
            return ($response->getStatusCode());
        } catch (ClientException $e) {
            return Psr7\Message::toString($e->getResponse());
        }
    }

    public function update(
        string $resource,
        string $id = NULL,
        array $data,
        bool $authBasic = FALSE,
        bool $authJWT = FALSE
    ): int|string
    {
        $data = json_encode($data);
        $path = $id ? $resource . '/' . $id : $resource;
        try {
            $request = new Request('PUT', $path, $this->headers, $data);
            $response = $this->client->send($request);
            return ($response->getStatusCode());
        } catch (ClientException $e) {
            return Psr7\Message::toString($e->getResponse());
        }
    }
    public function delete(
        string $resource,
        string $id = NULL,
        bool $authBasic = FALSE,
        bool $authJWT = FALSE
    ): int|string
    {
        $path = $id ? $resource . '/' . $id : $resource;
        try {
            $request = new Request('DELETE', $path, $this->headers);
            $response = $this->client->send($request);
            return ($response->getStatusCode());
        } catch (ClientException $e) {
            return Psr7\Message::toString($e->getResponse());
        }
    }

}