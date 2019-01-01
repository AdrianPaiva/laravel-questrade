<?php

namespace Tests\Feature\Auth;

class PasswordLoginApiTest extends AuthAPITestCase
{
    /**
     * @return string
     */
    private function getUrl()
    {
        return route('api:v1:auth:login', [
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
            'uuid'         => $this->user->uuid,
            'password'     => $data['password'] ?? null,
            'udid'         => '14F5563E-F5A6-4824-9B6E-E56B31310F7F',
            'device_type'  => $data['device_type'] ?? 'ios',
            'os_version'   => '12.1',
            'model_number' => 'iPhone9,3',
        ];
    }
    
    /**
     * @test
     */
    public function no_payload_gives_error_response()
    {
        $this->postJson($this->getUrl())
            ->assertStatus(422)
            ->assertJson([
                'message' => [
                    'uuid'         => [
                        'The uuid field is required.',
                    ],
                    'password'     => [
                        'The password field is required.',
                    ],
                    'udid'         => [
                        'The udid field is required.',
                    ],
                    'device_type'  => [
                        'The device type field is required.',
                    ],
                    'os_version'   => [
                        'The os version field is required.',
                    ],
                    'model_number' => [
                        'The model number field is required.',
                    ],
                ],
            ]);
    }
    
    /**
     * @test
     */
    public function it_gives_error_on_invalid_device_type()
    {
        $payload = $this->getPayload(['device_type' => 'foo']);
        
        $this->verifyDeviceType($this->getUrl(), $payload);
    }
    
    /**
     * @test
     */
    public function it_gives_error_on_invalid_password()
    {
        $this->postJson($this->getUrl(), $this->getPayload(['password' => 'wrong password']))
            ->assertStatus(422)
            ->assertJson([
                'message' => 'Password is Incorrect.',
            ]);
    }
    
    
    /**
     * @test
     */
    public function it_gives_user_details_with_access_token_and_domain_when_domain_tfa_is_disabled()
    {
        $this->domain->domainSetting->update(['tfa_enabled' => false]);
        
        $this->postJson($this->getUrl(), $this->getPayload(['password' => 'secret@123']))
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
    public function it_gives_user_details_without_access_token_and_domain_when_domain_tfa_is_enabled()
    {
        $response = $this->postJson($this->getUrl(), $this->getPayload(['password' => 'secret@123']));
        
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
