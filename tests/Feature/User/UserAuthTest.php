<?php

namespace Tests\Feature\User;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use App\Repositories\UserRepository;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        Role::factory()->create();
        $user = UserRepository::create([
            'ulid' => Str::ulid()->toBase32(),
            'id' => intval(User::max('id')),
            'name' => fake()->firstName(),
            'surname' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => '1122334455',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'birthDate' => fake()->dateTime(),
            'country' => fake()->country(),
            'city' => fake()->city(),
            'homeAdress' => fake()->streetAddress(),
            'phone' => fake()->phoneNumber(),
            'profilePicture' => '/media/images/pfp/default_user.png',
            'remember_token' => Str::random(10),
            'token' => Str::random(rand(16, 64)),
            'expiresIn' => Carbon::now()->addMinutes(env('APP_USER_REFRESH'))->format('Y-m-d H:i:s'),
            'email_verified_at' => null
        ], 'default');

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
