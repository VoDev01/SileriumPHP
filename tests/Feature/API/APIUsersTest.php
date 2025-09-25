<?php

namespace Tests\Feature\API;

use DateTime;
use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Laravel\Passport\Passport;
use App\Enum\TestAPIRouteMethods;
use Illuminate\Support\Facades\DB;
use App\Actions\TestAPIRouteForAuth;
use Laravel\Passport\ClientRepository;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class APIUsersTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSearch()
    {
        $role = Role::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $admin = User::factory()->hasAttached($role, [], 'roles')->create();

        $response = TestAPIRouteForAuth::test(
            '/api/v1/user/search/',
            TestAPIRouteMethods::POST,
            ['email' => $user->email],
            $this
        );

        $response->assertSessionHasNoErrors();

        $response->assertOk()
            ->assertJson(
                fn(AssertableJson $json) =>
                $json->has(
                    'users',
                    1,
                    fn($json) =>
                    $json->where('email', $user->email)
                        ->etc()
                )
                    ->etc()
            );
    }
}
