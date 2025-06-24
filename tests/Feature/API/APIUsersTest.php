<?php

namespace Tests\Feature\API;

use DateTime;
use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\DB;
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
        Passport::actingAs($admin, ['create']);

        $clientRepository = new ClientRepository();
        $client = $clientRepository->createPersonalAccessClient(
            $admin->id, 'Test Personal Access Client', 'http://localhost'
        );

        DB::table('oauth_personal_access_clients')->insert([
            'client_id' => $client->id,
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);

        $admin->createToken($admin->email . ' token');
        $secret = $admin->tokens->first()->id;

        $response = $this->withHeader('Api-Secret', $secret)->postJson('/api/v1/user/search/', ['email' => $user->email]);
        
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
