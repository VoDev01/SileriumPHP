<?php

namespace Tests\Unit\Actions;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CheckUserRoleActionTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $roles = array(Role::factory()->create(), Role::factory()->create(['role' => 'admin']));
        $user = User::factory()->hasAttached($roles, [], 'roles')->create();

        $this->assertFalse($user->hasRoles('seller'));

        $this->assertTrue($user->hasRoles(['user', 'admin']));
    }
}
