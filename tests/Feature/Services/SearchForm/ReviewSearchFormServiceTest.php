<?php

namespace Tests\Feature\Services\SearchForm;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Review;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Enum\TestRouteMethods;
use App\Services\SearchForms\ReviewSearchFormService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\WithFaker;
use App\Services\Testing\TestRouteForAuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReviewSearchFormServiceTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSearchProductReviews()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $seller = Seller::factory()->has(Product::factory(20))->create()->first();
        $product = $seller->products->first();

        $user = User::factory(15)->hasAttached(Role::factory()->create(['role' => 'user']), relationship: 'roles')->create()->first();

        $review = Review::factory(15)->for($user)->for($product)->create()->first();

        $admin = User::factory()->hasAttached(Role::factory()->create(['role' => 'admin']), relationship: 'roles')->create();
        
        $response = (new ReviewSearchFormService)->searchProductReviews([
            'productName' => $product->name,
            'sellerName' => $seller->nickname
        ]);

        $result = false;
        foreach($response as $data)
        {
            if($data->ulid === $review->ulid)
                $result = true;
        }

        $this->assertTrue($result);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSearchUserReviews()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $seller = Seller::factory()->has(Product::factory(20))->create()->first();
        $product = $seller->products->first();

        $user = User::factory(15)->hasAttached(Role::factory()->create(['role' => 'user']), relationship: 'roles')->create()->first();

        $review = Review::factory(15)->for($user)->for($product)->create()->first();

        $admin = User::factory()->hasAttached(Role::factory()->create(['role' => 'admin']), relationship: 'roles')->create();
        
        $response = (new ReviewSearchFormService)->searchUserReviews([
            'userId' => $user->ulid,
            'userEmail' => $user->email
        ]);

        $result = false;
        foreach($response as $data)
        {
            if($data->ulid === $review->ulid)
                $result = true;
        }

        $this->assertTrue($result);
    }

    public function testSearchSellerReviews()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $seller = Seller::factory()->has(Product::factory(20))->create()->first();
        $product = $seller->products->first();

        $user = User::factory(15)->hasAttached(Role::factory()->create(['role' => 'user']), relationship: 'roles')->create()->first();

        $review = Review::factory(15)->for($user)->for($product)->create()->first();

        $admin = User::factory()->hasAttached(Role::factory()->create(['role' => 'admin']), relationship: 'roles')->create();
        
        $response = (new ReviewSearchFormService)->searchSellerReviews([
            'sellerId' => $seller->ulid,
            'sellerEmail' => $seller->organization_email
        ]);

        $result = false;
        foreach($response as $data)
        {
            if($data->ulid === $review->ulid)
                $result = true;
        }

        $this->assertTrue($result);
    }
}
