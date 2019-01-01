<?php

namespace Tests\Feature\Auth;

use Illuminate\Routing\Middleware\ValidateSignature;

class UserInfoApiTest extends AuthAPITestCase
{
    /**
     * @param null $user_uuid
     *
     * @return string
     */
    private function getUrl($user_uuid = null)
    {
        return route('api:v1:auth:user-info', [
            'domain' => $this->domain->sub_domain,
            'user'   => $user_uuid ?? $this->user->uuid,
        ]);
    }
    
    /**
     * @test
     */
    public function it_gives_not_found_error_on_invalid_user_uuid()
    {
        $this->getJson($this->getUrl('123456'))
            ->assertStatus(404)
            ->assertJson([
                'message' => 'The requested record could not be found',
            ]);
    }
    
    /**
     * @test
     */
    public function it_gives_error_response_on_invalid_signature()
    {
        $this->getJson($this->getUrl())
            ->assertStatus(403)
            ->assertJson([
                'message' => 'Invalid signature.',
            ]);
    }
    
    /**
     * @test
     */
    public function it_gives_user_data_on_valid_user_uuid()
    {
        $this->withoutMiddleware(ValidateSignature::class);
        
        $this->getJson($this->getUrl())
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'user'   => $this->user_structure,
                    'domain' => $this->domain_structure,
                ],
            ]);
    }
}
