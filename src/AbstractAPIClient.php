<?php

namespace App\src;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

use GuzzleHttp\Client;
use App\auth\Basic;
use App\auth\JWT;

class AbstractAPIClient
{
    public Client $client;
    public array $headers;
    public function __construct()
    {
        $this->headers = [
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ];
        $this->client = new Client(['base_uri' => API_URL]);
    }
    public function get(
        string $resource,
        string $id = NULL,
        bool $authBasic = FALSE,
        bool $authJWT = FALSE
    ): mixed
    {
        if ($authBasic) {
            $this->headers['headers'] +=
                [
                    'Authorization' => (new Basic)->createAuth()
                ];
        } elseif ($authJWT) {
            $this->headers['headers'] +=
                [
                    'Authorization' => (new JWT)->createAuth()
                ];
        }
    }

    public function create(
        string $resource,
        string $id = NULL,
        array $data,
        bool $authBasic = FALSE,
        bool $authJWT = FALSE
    ): mixed
    {
        if ($authBasic) {
            $this->headers['headers'] +=
                [
                    'Authorization' => (new Basic)->createAuth()
                ];
        } elseif ($authJWT) {
            $this->headers['headers'] +=
                [
                    'Authorization' => (new JWT)->createAuth()
                ];
        }

    }
    public function update(
        string $resource,
        string $id = NULL,
        array $data,
        bool $authBasic = FALSE,
        bool $authJWT = FALSE
    )
    {
        if ($authBasic) {
            $this->headers['headers'] +=
                [
                    'Authorization' => (new Basic)->createAuth()
                ];
        } elseif ($authJWT) {
            $this->headers['headers'] +=
                [
                    'Authorization' => (new JWT)->createAuth()
                ];
        }
    }
    public function delete(
        string $resource,
        string $id = NULL,
        bool $authBasic = FALSE,
        bool $authJWT = FALSE
    )
    {
        if ($authBasic) {
            $this->headers['headers'] +=
                [
                    'Authorization' => (new Basic)->createAuth()
                ];
        } elseif ($authJWT) {
            $this->headers['headers'] +=
                [
                    'Authorization' => (new JWT)->createAuth()
                ];
        }
    }
}