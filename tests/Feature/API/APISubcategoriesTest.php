<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Category;
use App\Models\UserApiKey;
use App\Models\Subcategory;
use Laravel\Passport\Passport;
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
        Passport::actingAs(User::factory()->has(Role::factory())->create(), ['index']);
        $subcategories = Subcategory::factory()->count(20)->create();
        $subcategory = $subcategories->first();

        $response = $this->getJson('/api/v1/subcategories/index/15');

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
        Passport::actingAs(User::factory()->has(Role::factory())->create(), ['show']);
        $subcategory = Subcategory::factory()->create();

        $response = $this->getJson('/api/v1/subcategories/show/' . $subcategory->id);

        $response->assertOk();

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
        Passport::actingAs(User::factory()->hasAttached($role, [], 'roles')->create(), ['create']);
        $subcategory = Subcategory::factory()->create();

        $response = $this->postJson('/api/v1/subcategories/create', 
            [
                'name' => $subcategory->name, 
                'image' => $subcategory->image, 
                'categoryId' => $subcategory->category_id
            ]
        );

        $response->assertOk();
    }
    
    public function testUpdate()
    {
        Category::factory()->create();
        $role = Role::factory()->create(['role' => 'admin']);
        Passport::actingAs(User::factory()->hasAttached($role, [], 'roles')->create(), ['update']);
        $subcategory = Subcategory::factory()->create();

        $response = $this->patchJson('/api/v1/subcategories/update', 
            [
                'id' => $subcategory->id, 
                'name' => $subcategory->name, 
                'image' => $subcategory->image
            ]
        );

        $response->assertOk();
    }

    public function testDelete()
    {   
        Category::factory()->create();
        $role = Role::factory()->create(['role' => 'admin']);
        Passport::actingAs(User::factory()->hasAttached($role, [], 'roles')->create(), ['delete']);
        $subcategory = Subcategory::factory()->create();

        $response = $this->deleteJson('/api/v1/subcategories/delete', ['id' => $subcategory->id]);

        $response->assertOk();
    }
}
