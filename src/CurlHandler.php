<?php

namespace App\src;

use CurlHandle;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Utils;

class CurlHandler
{
    public $ch;

    public function sendRequest(Request $request)
    {
        $this->ch = curl_init();
        $options = [
            CURLOPT_URL => $request->getUri()->__toString(),
            CURLOPT_CUSTOMREQUEST => $request->getMethod(),
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HEADER => TRUE,
        ];
        foreach ($request->getHeaders() as $name => $values) {
            foreach ($values as $key => $value) {
                $options[CURLOPT_HTTPHEADER][] =
                    sprintf('%s: %s', $name, $value)
                ;
            }
        }
        if ($options[CURLOPT_CUSTOMREQUEST] == 'POST' || 'PUT') {
            $options[CURLOPT_POSTFIELDS] = $request->getBody()->__toString();
        }
        curl_setopt_array($this->ch, $options);
        curl_exec($this->ch);
        return $this->ch;
    }

    public function getResponse(CurlHandle $curl)
    {
        $status_code = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        $request_time = curl_getinfo($curl, CURLINFO_TOTAL_TIME);
        $message = curl_multi_getcontent($curl);
        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $header = substr($message, 0, $headerSize);
        $stream = Utils::streamFor(substr($message, $headerSize));

        $response = new Response($status_code);
        $response = $response->withAddedHeader('X-Request-Time', sprintf('%.3f ms', $request_time * 1000));

        $fields = explode("\r\n", $header);
        foreach ($fields as $i => $field) {
            if ($i === 0) {
                continue;
            }
            if ($field === '') {
                break;
            }
            if ($field[0] === ':') {
                continue;
            }
            $temp_field = explode(':', $field, 2);

            $response = $response->withAddedHeader($temp_field[0], $temp_field[1]);
        }

        $response = $response->withBody($stream);

        return $response;

    }
}