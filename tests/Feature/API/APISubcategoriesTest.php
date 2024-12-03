<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\Category;
use App\Models\Role;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class APISubcategoriesTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndex()
    {
        Category::factory()->create();
        $user = User::factory()->has(Role::factory())->create();
        $subcategories = Subcategory::factory()->count(20)->create();
        $subcategory = $subcategories->first();

        $response = $this->actingAs($user)->getJson('/api/v1/subcategories/index/15');

        $response
            ->assertOk()
            ->assertJson(fn(AssertableJson $json) => 
                $json->has('data', 15, fn ($json) => 
                    $json->where('name', $subcategory->name)
                    ->where('id', $subcategory->id)
                    ->etc()
            )
            ->etc()
        );
    }

    public function testShow()
    {
        Category::factory()->create();
        $user = User::factory()->has(Role::factory())->create();
        $subcategory = Subcategory::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/v1/subcategories/show/' . $subcategory->id);

        $response->assertStatus(200);

        $response->assertJson(fn(AssertableJson $json) => 
            $json->where('name', $subcategory->name)
                ->where('id', $subcategory->id)
                ->etc()
        );
    }

    public function testCreate()
    {
        Category::factory()->create();
        $role = Role::factory()->create(['role' => 'admin']);
        $user = User::factory()->hasAttached($role, [], 'roles')->create();
        $subcategory = Subcategory::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/v1/subcategories/create', ['name' => $subcategory->name, 'image' => $subcategory->image, 'categoryId' => $subcategory->category_id]);

        $response->assertStatus(200);
    }
    
    public function testUpdate()
    {
        Category::factory()->create();
        $role = Role::factory()->create(['role' => 'admin']);
        $user = User::factory()->hasAttached($role, [], 'roles')->create();
        $subcategory = Subcategory::factory()->create();

        $response = $this->actingAs($user)->putJson('/api/v1/subcategories/update', ['id' => $subcategory->id, 'name' => $subcategory->name, 'image' => $subcategory->image]);

        $response->assertStatus(200);
    }

    public function testDelete()
    {   
        Category::factory()->create();
        $role = Role::factory()->create(['role' => 'admin']);
        $user = User::factory()->hasAttached($role, [], 'roles')->create();
        $subcategory = Subcategory::factory()->create();

        $response = $this->actingAs($user)->deleteJson('/api/v1/subcategories/delete', ['id' => $subcategory->id]);

        $response->assertStatus(200);
    }
}
