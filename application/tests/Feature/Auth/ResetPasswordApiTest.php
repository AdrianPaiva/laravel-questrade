<?php

namespace Tests\Feature\Auth;

use App\Services\ResetPasswordService;
use Illuminate\Foundation\Testing\WithFaker;

class ResetPasswordApiTest extends AuthAPITestCase
{
    use WithFaker;
    
    /**
     * @var string
     */
    private $token;
    
    protected function setUp()
    {
        parent::setUp();
        
        $this->token = $this->app->make(ResetPasswordService::class)->createToken($this->user);
    }
    
    
    /**
     * @param null $user_uuid
     * @param null $token
     *
     * @return string
     */
    private function getUrl($user_uuid = null, $token = null)
    {
        return route('api:v1:auth:password.reset', [
            'domain' => $this->domain->sub_domain,
            'user'   => $user_uuid ?? $this->user->uuid,
            'token'  => $token ?? $this->token,
        ]);
    }
    
    /**
     * @param $password
     * @param null $password_confirmation
     *
     * @return array
     */
    private function getPayload($password, $password_confirmation = null)
    {
        return [
            'password'              => $password,
            'password_confirmation' => $password_confirmation ?? $password,
        ];
    }
    
    /**
     * @test
     */
    public function it_gives_validation_errors_if_no_payload()
    {
        $this->postJson($this->getUrl())
            ->assertStatus(422)
            ->assertJson([
                'message' => [
                    'password' => [
                        'The password field is required.',
                    ],
                ],
            ]);
    }
    
    /**
     * @test
     */
    public function it_gives_validation_errors_when_password_confrimation_do_not_match()
    {
        $this->postJson($this->getUrl(), $this->getPayload('password', 'wrong password'))
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
        $this->postJson($this->getUrl(), $this->getPayload('123', '123'))
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
    public function it_gives_not_found_error_on_invalid_user_uuid()
    {
        $this->postJson($this->getUrl('123456'))
            ->assertStatus(404)
            ->assertJson([
                'message' => 'The requested record could not be found',
            ]);
    }
    
    /**
     * @test
     */
    public function it_gives_error_response_on_invalid_token()
    {
        $this->postJson($this->getUrl(null, $this->faker->uuid), $this->getPayload('password'))
            ->assertStatus(422)
            ->assertJson([
                'message' => 'This password reset token is invalid.',
            ]);
    }
    
    /**
     * @test
     */
    public function it_gives_user_info_with_access_token_and_domain()
    {
        $password = 'sample@123';
        
        $this->postJson($this->getUrl(), $this->getPayload($password))
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'user'   => $this->user_structure,
                    'access_token',
                    'domain' => $this->domain_structure,
                ],
            ]);
     
        // Check if the Password has been reset
        $this->postJson(route('api:v1:auth:login', ['domain' => $this->domain->sub_domain]), [
            'uuid'         => $this->user->uuid,
            'password'     => $password,
            'udid'         => '14F5563E-F5A6-4824-9B6E-E56B31310F7F',
            'device_type'  => 'ios',
            'os_version'   => '12.1',
            'model_number' => 'iPhone9,3',
        ])->assertStatus(200);
    }
}
