<?php

namespace App\Services\External;

use App\Models\External\ApiClient;
use App\Services\BaseService;

class ApiService extends BaseService
{
    protected $base_url;
    protected $version;
    protected $access_token;
    protected $client;

    public function __construct(string $access_token, string $base_url, $version = null)
    {
        $this->setAccessToken($access_token);
        $this->setBaseUrl($base_url);
        $this->setVersion($version);
        
        $this->client = new ApiClient($this->getAccessToken());
    }

    public function getBaseUrl()
    {
        return $this->base_url;
    }

    public function setBaseUrl(string $base_url)
    {
        $this->base_url = $base_url;
    }

    public function getCompleteUrl()
    {
        $url = $this->normalizeUrlEndBar($this->getBaseUrl());
        $version = $this->normalizeUrlEndBar($this->getVersion());
        
        return $url . $version;
    }
    
    public function getVersion()
    {
        return $this->version;
    }
    
    public function setVersion($version)
    {
        $this->version = $version;
    }
    
    public function getClient()
    {
        return $this->client;
    }

    public function setClient(ApiClient $client)
    {
        $this->client = $client;
    }

    /**
     * Normalize an url string
     *
     * If it doesn't end with '/', add it
     *
     * @param $string
     * @return string
     */
    protected function normalizeUrlEndBar(string $url)
    {
        return (substr($url, -1) != '/') ? $url.'/': $url;
    }

    public function getAccessToken()
    {
        return $this->access_token;
    }

    public function setAccessToken(string $access_token)
    {
        $this->access_token = $access_token;
    }
}
