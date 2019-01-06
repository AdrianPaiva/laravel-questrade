<?php
namespace App\Services\External;

use App\Models\External\ApiResponse;
use App\Services\External\ApiService;

class QuestradeService extends ApiService
{
    public function __construct(string $access_token, string $base_url, string $version = "v1")
    {
        $this->setAccessToken($access_token);
        $this->setBaseUrl($base_url);
        $this->setVersion($version);

        parent::__construct($access_token);
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
