<?php

namespace Tests\Feature\Seller;

use Tests\TestCase;
use App\Models\User;
use App\Models\Seller;
use App\Enum\TaxSystem;
use App\Services\UserService;
use App\Enum\OrganizationType;
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
        $user = UserService::make(User::factory()->make()->toArray(), 'default');
        Seller::factory()->create(['user_id' => $user->id]);

        $response = $this->post("/seller/login", ["email" => "kar332@gmail.com", "password" => "1122334455"]);

        $response->assertInvalid(["email"]);

        $response = $this->post("/seller/login", ["email" => $user->email, "password" => "1122334456654"]);
        
        $response->assertInvalid(["password"]);

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
