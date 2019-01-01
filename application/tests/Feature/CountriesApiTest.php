<?php

namespace Tests\Feature;

use Tests\TestCase;

class CountriesApiTest extends TestCase
{
    /**
     * @test
     */
    public function it_get_list_of_countries()
    {
        $this->getJson(route('api:v1:countries'))
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'countries' => [
                        [
                            'name',
                            'iso_code',
                            'dial_code',
                            'flag',
                        ],
                    ],
                ],
            ]);
    }
}
