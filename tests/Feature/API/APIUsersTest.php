<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
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
        $user = User::factory()->hasAttached($role, [], 'roles')->create();

        $response = $this->actingAs($user)->postJson('/api/v1/user/search/', ['email' => $user->email]);
        
        $response->assertSessionHasNoErrors();

        $response->assertOk()
        ->assertJson(fn (AssertableJson $json) =>
            $json->has('users', 1, fn($json) =>
                $json->where('email', $user->email)
                    ->etc()
            )
            ->etc()
        );
    }
}
