<?php

namespace Tests\Feature\Auth;

class LogoutApiTest extends AuthAPITestCase
{
    /**
     * @return string
     */
    private function getUrl()
    {
        return route('api:v1:logout', [
            'domain' => $this->domain->sub_domain,
        ]);
    }
    
    /**
     * @test
     */
    public function it_gives_error_if_user_is_not_logged_in()
    {
        $this->getJson($this->getUrl())
            ->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }
    
    /**
     * @test
     */
    public function it_loggs_out_user()
    {
        $token_result = $this->user->createToken('Acto User', ['*']);
        
        $this->getJson($this->getUrl(), [
            'Authorization' => "Bearer {$token_result->accessToken}",
        ])->assertStatus(200);
        
        $this->assertDatabaseHas('oauth_access_tokens', [
            'id'      => $token_result->token->id,
            'revoked' => true,
        ]);
    }
}
