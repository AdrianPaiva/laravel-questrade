<?php

namespace Tests\Feature\Auth;

class DomainDetailsApiTest extends AuthAPITestCase
{
    /**
     * @param $domain_uuid
     *
     * @return string
     */
    private function getUrl($domain_uuid = null)
    {
        return route('api:v1:auth:domain-deails', [
            'uuid' => $domain_uuid,
        ]);
    }
    
    /**
     * @test
     */
    public function no_uuid_gives_error_response()
    {
        $this->getJson($this->getUrl())
            ->assertStatus(422)
            ->assertJson([
                'message' => [
                    'uuid' => ['The uuid field is required.'],
                ],
            ]);
    }
    
    /**
     * @test
     */
    public function invalid_uuid_gives_error_response()
    {
        $this->getJson($this->getUrl('1234567890'))
            ->assertStatus(422)
            ->assertJson([
                'message' => [
                    'uuid' => ['The selected uuid is invalid.'],
                ],
            ]);
    }
    
    /**
     * @test
     */
    public function valid_uuid_return_domain_details()
    {
        $response = $this->getJson($this->getUrl($this->domain->uuid));
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'domain' => $this->domain_structure,
                ],
            ]);
        
        $response->assertJson([
            'data' => [
                'domain' => [
                    'uuid' => $this->domain->uuid,
                ],
            ],
        ]);
    }
}
