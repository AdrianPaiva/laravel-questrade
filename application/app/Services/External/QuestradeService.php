<?php
namespace App\Services\External;

use App\Models\External\ApiResponse;
use App\Services\External\ApiService;

class QuestradeService extends ApiService
{
    public function __construct(string $api_key, string $base_url = "https://api01.iq.questrade.com/", string $version = "v1")
    {
        $this->setApiKey($api_key);
        $this->setBaseUrl($base_url);
        $this->setVersion($version);

        parent::__construct($api_key);
    }

    /**
     * List environments
     * 
     * @return Response
     */
    public function listEnvironments()
    {
        return $this->client->request(
            'GET',
            $this->getMountedBaseUrl() . "environments/",
            ['query' => ['version' => $this->date_version]]
        );
    }
}
