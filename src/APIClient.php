<?php

namespace App\src;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

use App\src\CurlHandler;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\ClientException;
use App\auth\Basic;
use App\auth\JWT;

class APIClient
{
    public array $headers;
    public string $path;
    public function __construct()
    {
        $this->headers['Content-Type'] = 'application/json';
    }
    public function get(
        string $resource,
        string $id = NULL,
        string $auth = NULL
    ): mixed
    {
        $this->auth($auth);

        $this->getUrl($resource, $id);

        try {
            $request = new Request('GET', $this->path, $this->headers);
            $curl = new CurlHandler;
            $handle = $curl->sendRequest($request);
            $response = $curl->getResponse($handle);
            return json_decode($response->getBody()->getContents());
        } catch (ClientException $e) {
            return Psr7\Message::toString($e->getResponse());
        }


    }

    public function create(
        string $resource,
        string $id = NULL,
        array $data,
        string $auth = NULL
    )
    {
        $data = json_encode($data);
        $this->auth($auth);
        $this->getUrl($resource, $id);
        try {
            $request = new Request('POST', $this->path, $this->headers, $data);
            $curl = new CurlHandler;
            $handle = $curl->sendRequest($request);
            $response = $curl->getResponse($handle);
            echo '<pre>';
            print_r($request->getBody()->__toString());
            echo '</pre>';
            print_r($response->getHeaders());
        } catch (ClientException $e) {
            return Psr7\Message::toString($e->getResponse());
        }
    }

    public function update(
        string $resource,
        string $id = NULL,
        array $data,
        string $auth = NULL
    )
    {
        $data = json_encode($data);
        $this->auth($auth);
        $this->getUrl($resource, $id);
        try {
            $request = new Request('PUT', $this->path, $this->headers, $data);
            $curl = new CurlHandler;
            $handle = $curl->sendRequest($request);
            $response = $curl->getResponse($handle);
        } catch (ClientException $e) {
            return Psr7\Message::toString($e->getResponse());
        }
    }
    public function delete(
        string $resource,
        string $id = NULL,
        string $auth = NULL
    ): int|string
    {
        $this->auth($auth);
        $this->getUrl($resource, $id);
        try {
            $request = new Request('DELETE', $this->path, $this->headers);
            $curl = new CurlHandler;
            $handle = $curl->sendRequest($request);
            $response = $curl->getResponse($handle);
        } catch (ClientException $e) {
            return Psr7\Message::toString($e->getResponse());
        }
    }

    public function auth(string|null $authType)
    {
        if ($authType == 'Basic') {
            $this->headers['Authorization'] = (new Basic)->createAuth();
        } elseif ($authType == 'JWT') {
            $this->headers['Authorization'] = (new JWT)->createAuth();
        }
    }

    public function getUrl(string $resource, string|null $id)
    {
        $this->path = $id ? API_URL . $resource . '/' . $id : API_URL . $resource;
    }

}