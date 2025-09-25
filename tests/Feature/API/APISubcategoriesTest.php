<?php

namespace Tests\Feature\API;

use App\Actions\TestAPIRouteForAuth;
use App\Enum\TestAPIRouteMethods;
use DateTime;
use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\APIUser;
use App\Models\Category;
use App\Models\UserApiKey;
use App\Models\Subcategory;
use Illuminate\Support\Str;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\ClientRepository;
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

        $response = TestAPIRouteForAuth::test(
            '/api/v1/subcategories/index/15',
            TestAPIRouteMethods::GET,
            null,
            $this
        );

        $response
            ->assertOk()
            ->assertJson(
                fn(AssertableJson $json) =>
                $json->has(
                    'data',
                    15,
                    fn($json) =>
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

        $response = TestAPIRouteForAuth::test(
            '/api/v1/subcategories/show/' . $subcategory->id,
            TestAPIRouteMethods::GET,
            null,
            $this
        );

        $response->assertJson(
            fn(AssertableJson $json) =>
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

        TestAPIRouteForAuth::test(
            '/api/v1/subcategories/create',
            TestAPIRouteMethods::POST,
            [
                'name' => $subcategory->name,
                'image' => $subcategory->image,
                'categoryId' => $subcategory->category_id
            ],
            $this
        );
    }

    public function testUpdate()
    {
        Category::factory()->create();
        $role = Role::factory()->create(['role' => 'admin']);
        $user = User::factory()->hasAttached($role, [], 'roles')->create();
        $subcategory = Subcategory::factory()->create();

        TestAPIRouteForAuth::test(
            '/api/v1/subcategories/update',
            TestAPIRouteMethods::PATCH,
            [
                'id' => $subcategory->id,
                'name' => $subcategory->name,
                'image' => $subcategory->image
            ],
            $this
        );
    }

    public function testDelete()
    {
        Category::factory()->create();
        $role = Role::factory()->create(['role' => 'admin']);
        $user = User::factory()->hasAttached($role, [], 'roles')->create();
        $subcategory = Subcategory::factory()->create();

        TestAPIRouteForAuth::test(
            '/api/v1/subcategories/delete',
            TestAPIRouteMethods::DELETE,
            [
                'id' => $subcategory->id
            ],
            $this
        );
    }
}
