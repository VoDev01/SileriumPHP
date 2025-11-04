<?php

namespace Tests\Feature\Actions;

use App\Actions\ValidatePasswordHashAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class ValidatePasswordHashActionTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testValidatePassword()
    {
        $user = User::factory()->create();
        $this->assertEquals(['success' => false, 'errors' => ['password' => 'Пароль не совпадает.']], ValidatePasswordHashAction::validate('11223344', $user));
        $this->assertEquals(['success' => true], ValidatePasswordHashAction::validate('1122334455', $user));
    }
}
