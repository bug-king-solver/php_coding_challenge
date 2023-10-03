<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class RandomUserControllerTest extends TestCase
{

    public function testValidationFailsIfLimitIsMissing()
    {
        $response = $this->json('GET', '/api/random-users', []);

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['limit']);
    }

    public function testValidationFailsIfLimitIsNotAnInteger()
    {
        $response = $this->json('GET', '/api/random-users', ['limit' => 'abc']);

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['limit']);
    }
    public function testRandomUsersEndpoint()
    {
        // Mock the API response
        Http::fake([
            'https://randomuser.me/api/' => Http::response($this->getMockedUserData(), 200),
        ]);

        // Make a GET request to the API endpoint
        $response = $this->get('/api/random-users');

        // Assert the response status code
        $response->assertStatus(400);
    }

    private function getMockedUserData()
    {
        return [
            'results' => [
                [
                    'gender' => 'female',
                    'name' => [
                        'title' => 'Ms',
                        'first' => 'Caitlin',
                        'last' => 'Mendoza',
                    ],
                    'location' => [
                        'street' => [
                            'number' => 3927,
                            'name' => 'Rectory Lane',
                        ],
                        'city' => 'St Davids',
                        'state' => 'Nottinghamshire',
                        'country' => 'United Kingdom',
                        'postcode' => 'VQ0A 6PU',
                        'coordinates' => [
                            'latitude' => '-6.0511',
                            'longitude' => '75.3122',
                        ],
                        'timezone' => [
                            'offset' => '-11:00',
                            'description' => 'Midway Island, Samoa',
                        ],
                    ],
                    'email' => 'caitlin.mendoza@example.com',
                    'login' => [
                        'uuid' => '29c06bcb-7857-4595-af6f-87002de0a13c',
                        'username' => 'yellowleopard540',
                        'password' => 'hazard',
                        'salt' => 'IvCxJxy8',
                        'md5' => 'aa61743ae302513681250146c5d176db',
                        'sha1' => 'e4095b4841b63bba5f2385157c749ff5520566a0',
                        'sha256' => '95cb4c329253f299e7cc69569bd862fadc9294a19adab9fecc8049118f62ddd9',
                    ],
                    'dob' => [
                        'date' => '1948-03-07T08:51:41.711Z',
                        'age' => 75,
                    ],
                    'registered' => [
                        'date' => '2003-03-14T12:21:45.041Z',
                        'age' => 20,
                    ],
                    'phone' => '015395 19713',
                    'cell' => '07792 777047',
                    'id' => [
                        'name' => 'NINO',
                        'value' => 'EK 31 81 81 R',
                    ],
                    'picture' => [
                        'large' => 'https://randomuser.me/api/portraits/women/12.jpg',
                        'medium' => 'https://randomuser.me/api/portraits/med/women/12.jpg',
                        'thumbnail' => 'https://randomuser.me/api/portraits/thumb/women/12.jpg',
                    ],
                    'nat' => 'GB',
                ],
            ],
            'info' => [
                'seed' => '9a54c8d2333eae71',
                'results' => 1,
                'page' => 1,
                'version' => '1.4',
            ],
        ];
    }
}