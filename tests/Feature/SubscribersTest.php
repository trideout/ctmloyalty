<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class SubscribersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        //Create User
        $response = $this->post('/api/subscribers', [
            'email' => 'tom@test.com',
            'first_name' => 'Thomas',
            'last_name' => 'Rideout',
            'opt_in' => true,
        ]);
        $response->assertStatus(201);
        $response->assertJson(function(AssertableJson $json){
            $json->where('data.email', 'tom@test.com')
                ->where('data.first_name', 'Thomas')
                ->where('data.last_name', 'Rideout')
                ->where('data.opt_in', true);
        });
        //List Users
        $response = $this->get('/api/subscribers');
        $response->assertStatus(200);
        $response->assertJson(function(AssertableJson $json){
            $json->where('data.0.email', 'tom@test.com');
        });
        //Get User
        $response = $this->get('/api/subscribers/1');
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('data.email', 'tom@test.com');
        });
        //Update User
        $response = $this->patch('/api/subscribers/1', [
            'email' => 'tom@nothing.com',
            'opt_in' => false,
        ]);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('data.email', 'tom@nothing.com');
            $json->where('data.opt_in', false);
        });

        //Get updated user
        $response = $this->get('/api/subscribers/1');
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('data.email', 'tom@nothing.com');
            $json->where('data.opt_in', false);
        });
    }
}
