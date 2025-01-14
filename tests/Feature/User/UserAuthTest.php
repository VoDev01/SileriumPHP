<?php

namespace Tests\Feature\User;

use Tests\TestCase;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;

class UserAuthTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testLogin()
    {
        $user = UserService::make(User::factory()->make()->toArray(), 'default');

        $response = $this->post('/user/login', ['email' => 'harak32@gmail.com', 'password' => '1122334455']);

        $response->assertInvalid(['email']);

        $response = $this->post('/user/login', ['email' => $user->email, 'password' => '112233445566']);

        $response->assertInvalid(['password']);

        $response = $this->post('/user/login', ['email' => $user->email, 'password' => '1122334455']);

        $response->assertValid();

        $response->assertJson(fn(AssertableJson $json) =>
            $json->where('redirect', '/user/profile')
        );
    }

    public function testRegister()
    {
        $user = User::factory()->make();

        $response = $this->post('/user/register', [
            'name' => $user->name,
            'surname' => $user->surname,
            'email' => $user->email,
            'password' => $user->password,
            'password_confirmation' => $user->password,
            'birthDate' => $user->birthDate,
            'country' => $user->country,
            'city' => $user->city,
            'homeAdress' => $user->homeAdress,
            'phone' => $user->phone,
            'pfp' => $user->pfp,
        ]);

        $response->assertValid();

        $this->assertDatabaseHas('users', ['email' => $user->email]);
    }
}
