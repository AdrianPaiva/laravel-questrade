<?php

namespace Tests\Feature\Auth;

use App\Mail\MagicLinkMail;
use Illuminate\Support\Facades\Mail;

class GenerateMagicLinkApiTest extends AuthAPITestCase
{
    /**
     * @return string
     */
    private function getUrl()
    {
        return route('api:v1:auth:magic-link.generate', [
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
        $this->verifyUserUuid($this->getUrl(), $this->getPayload(['uuid' => 'wrong uuid']));
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
    public function is_sends_magic_link_via_email()
    {
        Mail::fake();
        
        $this->postJson($this->getUrl(), $this->getPayload())
            ->assertStatus(200)
            ->assertJson([
                'message' => "Magic Link Sent to your Email ID - {$this->user->email}.",
            ]);
        
        Mail::assertQueued(MagicLinkMail::class, function ($mail) {
            return $mail->hasTo($this->user->email)
                and $mail->user->is($this->user)
                and $mail->profile->is($this->profile)
                and !is_null($mail->url);
        });
        
        $this->assertDatabaseHas('magic_links', [
            'user_id'   => $this->user->id,
            'domain_id' => $this->domain->id,
        ]);
    }
}
