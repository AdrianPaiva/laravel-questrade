<?php

namespace Tests\Feature\Auth;

use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordApiTest extends AuthAPITestCase
{
    /**
     * @param null $user_uuid
     *
     * @return string
     */
    private function getUrl($user_uuid = null)
    {
        return route('api:v1:auth:password.send', [
            'domain' => $this->domain->sub_domain,
            'uuid' => $user_uuid
        ]);
    }
    
    /**
     * @test
     */
    public function it_gives_validation_errors_when_no_payload()
    {
        $this->getJson($this->getUrl())
            ->assertStatus(422)
            ->assertJson([
                'message' => [
                    'uuid' => [
                        'The uuid field is required.',
                    ],
                ],
            ]);
    }
    
    /**
     * @test
     */
    public function it_gives_validation_error_on_invalid_user_uuid()
    {
        $this->verifyUserUuid($this->getUrl('wrong uuid'), [], 'GET');
    }
    
    /**
     * @test
     */
    public function is_sends_reset_password_link_via_email()
    {
        Mail::fake();
        
        $this->getJson($this->getUrl($this->user->uuid))
            ->assertStatus(200)
            ->assertJson([
                'message' => "Reset Password Link have been Sent to your Email.",
            ]);
        
        Mail::assertSent(ResetPasswordMail::class, function ($mail) {
            return $mail->hasTo($this->user->email)
                and !is_null($mail->url);
        });
        
        $this->assertDatabaseHas('password_resets', [
            'email' => $this->user->email,
        ]);
    }
}
