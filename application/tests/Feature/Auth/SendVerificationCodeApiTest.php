<?php

namespace Tests\Feature\Auth;

use App\Jobs\SendVerificationCode;
use App\Mail\VerificationCodeMail;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;

class SendVerificationCodeApiTest extends AuthAPITestCase
{
    /**
     * @return string
     */
    private function getUrl()
    {
        return route('api:v1:auth:send-verification-code', [
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
            'uuid'           => $data['uuid'] ?? $this->user->uuid,
            'mobile'         => $data['mobile'] ?? null,
            'send_on_mobile' => $data['send_on_mobile'] ?? false,
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
                    'uuid'           => [
                        'The uuid field is required.',
                    ],
                    'send_on_mobile' => [
                        'The send on mobile field is required.',
                    ],
                ],
            ]);
    }
    
    /**
     * @test
     */
    public function it_gives_validation_errors_on_invalid_user_uuid()
    {
        $payload = $this->getPayload(['uuid' => 'wrong uuid']);
        
        $this->verifyUserUuid($this->getUrl(), $payload);
    }
    
    /**
     * @test
     */
    public function it_gives_validation_errors_if_mobile_is_incorrect()
    {
        $payload = $this->getPayload(['mobile' => '+us-123-134']);
        
        $this->postJson($this->getUrl(), $payload)
            ->assertStatus(422)
            ->assertJson([
                'message' => [
                    'mobile' => [
                        'The mobile field contains an invalid number.',
                    ],
                ],
            ]);
    }
    
    /**
     * @test
     */
    public function it_gives_validation_errors_if_mobile_is_not_unique()
    {
        $mobile = '+91-9876543210';
        
        $this->createUser([
            'mobile' => $mobile,
        ]);
        
        $payload = $this->getPayload(['mobile' => $mobile]);
        
        $this->postJson($this->getUrl(), $payload)
            ->assertStatus(422)
            ->assertJson([
                'message' => [
                    'mobile' => [
                        'The mobile has already been taken.',
                    ],
                ],
            ]);
    }
    
    /**
     * @test
     */
    public function it_gives_validation_errors_if_send_on_mobile_is_not_boolean()
    {
        $payload = $this->getPayload(['send_on_mobile' => 'not a boolean']);
        
        $this->postJson($this->getUrl(), $payload)
            ->assertStatus(422)
            ->assertJson([
                'message' => [
                    'send_on_mobile' => [
                        'The send on mobile field must be true or false.',
                    ],
                ],
            ]);
    }
    
    /**
     * @test
     */
    public function it_generates_and_sends_the_verification_code_on_email_only()
    {
        Mail::fake();
        
        $mobile = '+91-9876543210';
        
        $payload = $this->getPayload([
            'mobile' => $mobile,
        ]);
        
        $this->postJson($this->getUrl(), $payload)
            ->assertStatus(200)
            ->assertJson([
                'message' => 'Verification Code Sent.',
            ]);
        
        Mail::assertSent(VerificationCodeMail::class, function ($mail) {
            return $mail->hasTo($this->user->email)
                and $mail->user->is($this->user)
                and $mail->profile->is($this->profile)
                and !is_null($mail->code);
        });
        
        $this->assertDatabaseHas('users', [
            'uuid'   => $this->user->uuid,
            'mobile' => $mobile,
        ]);
    }
    
    /**
     * @test
     */
    public function it_generates_and_sends_the_verification_code_on_mobile_also()
    {
        Bus::fake();
        
        $mobile = '+91-9876543210';
        $send_on_mobile = true;
        
        $payload = $this->getPayload([
            'mobile'         => $mobile,
            'send_on_mobile' => $send_on_mobile,
        ]);
        
        $this->postJson($this->getUrl(), $payload)
            ->assertStatus(200)
            ->assertJson([
                'message' => 'Verification Code Sent.',
            ]);
        
        Bus::assertDispatched(SendVerificationCode::class, function ($job) use ($send_on_mobile) {
            return $job->user->is($this->user)
                and $job->domain->is($this->domain)
                and $job->send_on_mobile === $send_on_mobile;
        });
        
        $this->assertDatabaseHas('users', [
            'uuid'   => $this->user->uuid,
            'mobile' => $mobile,
        ]);
    }
}
