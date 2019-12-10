<?php

namespace App\Models\External;

use GuzzleHttp\Client as GuzzleClient;

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

    public function request(string $method, string $uri, array $options = [])
    {   
        return $this->response->parse($this->guzzle->request($method, $uri, array_merge($this->getOptions(), $options)));
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
