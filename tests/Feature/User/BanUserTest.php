<?php

namespace Tests\Feature\User;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BanUserTest extends TestCase
{
    use RefreshDatabase;
    
    public function testBan()
    {
        $adminRole = Role::factory()->create(['role' => 'admin']);
        $admin = User::factory()->hasAttached($adminRole, [], 'roles')->create();
        $user = User::factory()->hasAttached(Role::factory(), [], 'roles')->create();

        $response = $this->actingAs($admin)->post('/admin/users/ban', 
            [
                'user_id' => $user->ulid,
                'admin_id' => $admin->ulid,
                'reason' => 'Spam',
                'banTime' => 100,
                'timeType' => 'hours'
            ]
        );

        $response->assertRedirect('/admin/users/ban');

        $response->assertValid();

        $this->assertDatabaseHas('banned_users', ['user_id' => $user->ulid]);
    }
}
