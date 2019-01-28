<?php

namespace Tests\Feature\Auth;

class VerifyEmailApiTest extends AuthAPITestCase
{
    /**
     * @param array $params
     *
     * @return string
     */
    private function getUrl($params = [])
    {
        return route('api:v1:verify-email', $params);
    }
    
    /**
     * @test
     */
    public function no_email_gives_error_response()
    {
        $this->getJson($this->getUrl())
            ->assertStatus(422)
            ->assertJson([
                'message' => [
                    'email' => ['The email field is required.'],
                ],
            ]);
    }
    
    /**
     * @test
     */
    public function invalid_email_gives_error_response()
    {
        $this->createUser([
            'email' => 'jon@example.com',
        ]);
        
        $response = $this->getJson($this->getUrl([
            'email' => 'foo@example.com',
        ]));
        
        $response->assertStatus(422)
            ->assertJson([
                'message' => [
                    'email' => ['The selected email is invalid.'],
                ],
            ]);
    }
    
    /**
     * @test
     */
    public function valid_email_gives_domains_list()
    {
        $response = $this->getJson($this->getUrl([
            'email' => $this->user->email,
        ]));
        
        unset($this->user_structure['profile']);
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'user' => $this->user_structure,
                    
                    'domains' => [
                        $this->domain_structure,
                    ],
                ],
            ]);
        
        $response->assertJson([
            'data' => [
                'user' => [
                    'uuid' => $this->user->uuid,
                ],
            ],
        ]);
    }
}
