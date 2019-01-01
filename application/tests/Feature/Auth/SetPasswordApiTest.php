<?php

namespace Tests\Feature\Auth;

class SetPasswordApiTest extends AuthAPITestCase
{
    /**
     * @return string
     */
    private function getUrl()
    {
        return route('api:v1:auth:set-password', [
            'domain' => $this->domain->sub_domain,
        ]);
    }
    
    /**
     * @param array $data
     *
     * @return array
     */
    private function getPayload($data = [])
    {
        return [
            'uuid'                  => $data['uuid'] ?? $this->user->uuid,
            'password'              => $data['password'] ?? 'secret@123',
            'password_confirmation' => $data['password_confirmation'] ?? 'secret@123',
        ];
    }
    
    /**
     * @test
     */
    public function it_gives_validation_errors_when_no_payload()
    {
        $this->postJson($this->getUrl())
            ->assertStatus(422)
            ->assertJson([
                'message' => [
                    'uuid'     => [
                        'The uuid field is required.',
                    ],
                    'password' => [
                        'The password field is required.',
                    ],
                ],
            ]);
    }
    
    /**
     * @test
     */
    public function it_gives_validation_error_on_invalid_user_uuid()
    {
        $payload = $this->getPayload(['uuid' => 'wrong uuid']);
        
        $this->verifyUserUuid($this->getUrl(), $payload);
    }
    
    /**
     * @test
     */
    public function it_gives_validation_errors_when_password_confrimation_do_not_match()
    {
        $payload = $this->getPayload(['password_confirmation' => 'wrong password']);
        
        $this->postJson($this->getUrl(), $payload)
            ->assertStatus(422)
            ->assertJson([
                'message' => [
                    'password' => [
                        'The password confirmation does not match.',
                    ],
                ],
            ]);
    }
    
    /**
     * @test
     */
    public function it_gives_validation_errors_if_password_is_not_min_8_chars()
    {
        $payload = $this->getPayload([
            'password'              => '123',
            'password_confirmation' => '123',
        ]);
        
        $this->postJson($this->getUrl(), $payload)
            ->assertStatus(422)
            ->assertJson([
                'message' => [
                    'password' => [
                        'The password must be at least 8 characters.',
                    ],
                ],
            ]);
    }
    
    /**
     * @test
     */
    public function it_gives_error_response_if_user_is_already_verified()
    {
        $this->user->update(['verified' => true]);
        
        $payload = $this->getPayload();
        
        $this->postJson($this->getUrl(), $payload)
            ->assertStatus(422)
            ->assertJson([
                'message' => 'Password has Already been set for given User.',
            ]);
    }
    
    /**
     * @test
     */
    public function it_sets_the_password_and_gives_user_data_with_access_token_and_domain()
    {
        $this->domain->domainSetting->update(['tfa_enabled' => false]);
        
        $payload = $this->getPayload();
        
        $this->postJson($this->getUrl(), $payload)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'user'   => $this->user_structure,
                    'access_token',
                    'domain' => $this->domain_structure,
                ],
            ]);
    }
    
    /**
     * @test
     */
    public function it_sets_the_password_and_gives_user_data_without_access_token_and_domain()
    {
        $response = $this->postJson($this->getUrl(), $this->getPayload());
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'user' => $this->user_structure,
                ],
            ]);
        
        $response->assertJsonMissing([
            'domain' => $this->domain_structure,
            'access_token',
        ]);
    }
}
