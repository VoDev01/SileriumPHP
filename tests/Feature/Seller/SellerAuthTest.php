<?php

namespace Tests\Feature\Seller;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Seller;
use App\Enum\TaxSystem;
use Illuminate\Support\Str;
use App\Enum\OrganizationType;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SellerAuthTest extends TestCase
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
        Seller::factory()->create(['user_id' => $user->id]);

        $response = $this->post("/seller/login", ["email" => "kar332@gmail.com", "password" => "1122334455"]);

        $response->assertInvalid(["email"]);

        $response = $this->post("/seller/login", ["email" => $user->email, "password" => "1122334456654"]);

        $response->assertUnauthorized();

        $response = $this->post("/seller/login", ["email" => $user->email, "password" => "1122334455"]);

        $response->assertValid();
    }

    public function testRegister()
    {
        $user = User::factory()->create();

        $response = $this->post("seller/register", [
            "organization_email" => $user->email,
            "email" => $user->email,
            "nickname" => "Nikess",
            "organization_name" => "Brand shop OOO",
            "organization_type" => OrganizationType::OOO->value,
            "tax_system" => TaxSystem::OSNO->value,
            "user_id" => $user->id
        ]);

        $response->assertValid();

        $this->assertDatabaseHas("sellers", ["organization_email" => $user->email]);
    }
}
