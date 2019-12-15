<?php

namespace App\Models\External;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Promise\Promise;

class ApiClient
{
    private $guzzle;
    private $response;
    private $options = [];
    
    public function __construct(string $access_token = null)
    {
        $options = [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'http_errors' => false
        ];

        if ($access_token) {
            $options['headers']['Authorization'] = "Bearer {$access_token}";
        }

        $this->setOptions($options);
        
        $this->setGuzzle(new GuzzleClient());
        $this->setResponse(new ApiResponse());
    }

    public function request(string $method, string $uri, array $options = []): ApiResponse
    {   
        return $this->response->parse($this->guzzle->request($method, $uri, array_merge($this->getOptions(), $options)));
    }

    public function requestAsync(string $method, string $uri, array $options = []): Promise
    {   
        return $this->guzzle->requestAsync($method, $uri, array_merge($this->getOptions(), $options));
    }

    public static function parsePromises(array $promises)
    {
        $responses = collect(\GuzzleHttp\Promise\settle($promises)->wait());

        return $responses->map(function ($response) {
            $api_response = new ApiResponse();
            $api_response->parse($response['value']);

            return $api_response;
        });
    }

    public function setGuzzle(GuzzleClient $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions(array $options)
    {
        // $this->options = array_merge($this->getOptions(), $options);
        $this->options = $options;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse(ApiResponse $response)
    {
        $this->response = $response;
    }
}
