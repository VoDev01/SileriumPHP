<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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
        $user = User::factory()->create();

        $response = $this->post('/user/postlogin', ['email' => 'harak32@gmail.com', 'password' => '1122334455']);

        $response->assertInvalid(['email']);

        $response = $this->post('/user/postlogin', ['email' => $user->email, 'password' => '1122334455']);

        $response->assertValid();
    }

    public function testRegister()
    {
        $user = User::factory()->make();

        $response = $this->post('/user/postregister', [
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
