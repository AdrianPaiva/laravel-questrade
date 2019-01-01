<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;

abstract class AuthAPITestCase extends TestCase
{
    /**
     * @var array
     */
    protected $user_structure = [
        'uuid',
        'email',
        'mobile',
        'verified',
        'profile' => [
            'uuid',
            'first_name',
            'last_name',
            'full_name',
            'onboarded_date',
            'invitation_date',
            'deployment_date',
            'verified',
            'has_used_sso',
            'has_received_invitation',
            'auth_assignment',
        ],
    ];
    
    /**
     * @var array
     */
    protected $domain_structure = [
        'uuid',
        'base_url',
        'name',
        'sub_domain',
        'description',
        
        'domain_setting' => [
            'timezone',
            'quiz_passing_grade',
            'notification_comments_enabled',
            'tfa_enabled',
            'date_format',
        ],
        
        'applied_theme' => [
            'name',
            'color',
            'is_applied',
            'is_default_theme',
            'main_logo_url',
            'sidenav_logo_url',
            'background_image_url',
        ],
    ];
    
    protected function setUp()
    {
        parent::setUp();
        
        $this->createSampleData();
    }
    
    
    /**
     * @param $url
     * @param $payload
     * @param string $method
     * @param string $key
     */
    protected function verifyDeviceType($url, $payload, $method = 'POST', $key = 'device_type')
    {
        $this->json($method, $url, $payload)
            ->assertStatus(422)
            ->assertJson([
                'message' => [
                    $key => [
                        'The selected device type is invalid.',
                    ],
                ],
            ]);
    }
    
    /**
     * @param $url
     * @param $payload
     * @param string $key
     * @param string $method
     */
    protected function verifyUserUuid($url, $payload = [], $method = 'POST', $key = 'uuid')
    {
        $this->json($method, $url, $payload)
            ->assertStatus(422)
            ->assertJson([
                'message' => [
                    $key => [
                        'The selected uuid is invalid.',
                    ],
                ],
            ]);
    }
}
