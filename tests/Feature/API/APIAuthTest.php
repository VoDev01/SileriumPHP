<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\ApiUser;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class APIAuthTest extends TestCase
{
    use RefreshDatabase;

    public function testLogin()
    {
        $user = ApiUser::factory(10)->create()->first();

        $response = $this->post('/api/v1/login', ['email' => 'harak32@gmail.com', 'password' => '1122334455']);

        $response->assertInvalid(['email']);

        $response = $this->post('/api/v1/login', ['email' => $user->email, 'password' => '112233445566']);

        $response->assertInvalid(['password']);

        $response = $this->post('/api/v1/login', ['email' => $user->email, 'password' => '1122334455']);

        $response->assertValid();

        $response->assertRedirect('/api/v1/profile');
    }

    public function testRegister()
    {
        $user = ApiUser::factory()->make();

        $response = $this->post('/api/v1/register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
            'password_confirmation' => $user->password,
            'phone' => $user->phone
        ]);

        $response->assertValid();

        $this->assertDatabaseHas('api_users', ['email' => $user->email]);
    }
}