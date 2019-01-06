<?php

namespace App\Services\External;

use App\Models\External\ApiClient;
use App\Services\BaseService;

class ApiService extends BaseService
{
    protected $base_url;
    protected $version;
    protected $api_key;
    protected $client;

    public function __construct(string $api_key)
    {
        $this->setApiKey($api_key);
        $this->client = new ApiClient($this->getApiKey());
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
    
    public function setVersion(string $version)
    {
        $this->version = $version;
    }
    
    public function getClient()
    {
        return $this->client;
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

    public function getApiKey()
    {
        return $this->api_key;
    }

    public function setApiKey(string $api_key)
    {
        $this->api_key = $api_key;
    }
}
