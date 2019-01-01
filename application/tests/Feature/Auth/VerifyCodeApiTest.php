<?php

namespace Tests\Feature\Auth;

use App\Models\VerificationCode;

class VerifyCodeApiTest extends AuthAPITestCase
{
    /**
     * @return string
     */
    private function getUrl()
    {
        return route('api:v1:auth:verify-code', [
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
            'uuid'         => $data['uuid'] ?? $this->user->uuid,
            'code'         => $data['code'] ?? null,
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
                    'code'         => [
                        'The code field is required.',
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
    public function it_gives_validation_error_on_invalid_user_uuid()
    {
        $payload = $this->getPayload(['uuid' => 'wrong uuid']);
        
        $this->verifyUserUuid($this->getUrl(), $payload);
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
    public function it_gives_error_on_invalid_code()
    {
        $this->user->verificationCode()->save(
            factory(VerificationCode::class)->make([
                'code' => '123456',
            ])
        );
        
        $this->postJson($this->getUrl(), $this->getPayload(['code' => 'wrong code']))
            ->assertStatus(422)
            ->assertJson([
                'message' => 'Verification Code Does not match.',
            ]);
    }
    
    /**
     * @test
     */
    public function it_gives_error_on_code_expiration()
    {
        $code = '123456';
        
        $this->user->verificationCode()->save(
            factory(VerificationCode::class)->make([
                'code'       => $code,
                'expires_at' => now()->subMinute(1),
            ])
        );
        
        $this->postJson($this->getUrl(), $this->getPayload(['code' => $code]))
            ->assertStatus(422)
            ->assertJson([
                'message' => 'Verification Code Does not match.',
            ]);
    }
    
    /**
     * @test
     */
    public function is_gives_user_date_with_access_token_and_domain_on_valid_code()
    {
        $code = '123456';
        
        $this->user->verificationCode()->save(
            factory(VerificationCode::class)->make([
                'code' => $code,
            ])
        );
        
        $this->postJson($this->getUrl(), $this->getPayload(['code' => $code]))
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'user'   => $this->user_structure,
                    'access_token',
                    'domain' => $this->domain_structure,
                ],
            ]);
    }
}
