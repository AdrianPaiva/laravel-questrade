<?php

namespace Tests\Feature\Auth;

use App\Models\MagicLink;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Routing\Middleware\ValidateSignature;

class VerifyMagicLinkApiTest extends AuthAPITestCase
{
    use WithFaker;
    
    /**
     * @var \App\Models\MagicLink
     */
    private $magic_link;
    
    protected function setUp()
    {
        parent::setUp();
        
        $this->magic_link = factory(MagicLink::class)->create([
            'user_id'   => $this->user->id,
            'domain_id' => $this->domain->id,
        ]);
    }
    
    /**
     * @param array $params
     *
     * @return string
     */
    private function getUrl($params = [])
    {
        $params = array_merge([
            'domain'    => $this->domain->sub_domain,
            'user'      => $this->user->uuid,
            'magicLink' => $this->magic_link->token,
        ], $params);
        
        return route('api:v1:auth:verify-magic-link', $params);
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
    public function it_gives_error_response_on_invalid_user()
    {
        $this->getJson($this->getUrl(['user' => 'wrong uuid']))
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
        $url = $this->getUrl([
            'user'      => 'wrong uuid',
            'magicLink' => $this->faker->uuid,
        ]);
        
        $this->getJson($url)
            ->assertStatus(404)
            ->assertJson([
                'message' => 'The requested record could not be found',
            ]);
    }
    
    /**
     * @test
     */
    public function it_gives_error_if_token_is_already_used()
    {
        $this->withoutMiddleware(ValidateSignature::class);
        
        $this->magic_link->markAsUsed();
        
        $this->getJson($this->getUrl())
            ->assertStatus(422)
            ->assertJson([
                'message' => 'This Link has Already Expired.',
            ]);
    }
    
    /**
     * @test
     */
    public function it_gives_user_info_with_access_token_and_domain()
    {
        $this->withoutMiddleware(ValidateSignature::class);
        
        $url = $this->getUrl([
            'udid'         => '14F5563E-F5A6-4824-9B6E-E56B31310F7F',
            'device_type'  => 'ios',
            'os_version'   => '12.1',
            'model_number' => 'iPhone9,3',
        ]);
        
        $this->getJson($url)
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
