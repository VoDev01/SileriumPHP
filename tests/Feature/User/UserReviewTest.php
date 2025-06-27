<?php

namespace Tests\Feature\User;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Review;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Support\Str;
use App\Models\ReviewsImages;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserReviewTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testReview()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $user = User::factory()->has(Role::factory())->create();
        $productId = Seller::factory()->has(Product::factory())->create()->products()->first()->id;
        $review = Review::factory()->make();

        Storage::fake('reviews');

        $reviewImages = array();
        for($i = 0; $i < 3; $i++)
        {
            $reviewImages[$i] = UploadedFile::fake()->image('review_' . Str::ulid()->toBase32() . '.jpg', 2048, 1024)->size(10 * 1024);
        }
        $response = $this->actingAs($user)->post('/user/review', [
            'title' => $review->title,
            'pros' => $review->pros,
            'cons' => $review->cons,
            'comment' => $review->comment,
            'rating' => $review->rating,
            'product_id' => $productId,
            'review_images' => $reviewImages,
            'user_id' => $user->id,
        ]);

        for($i = 0; $i < 3; $i++)
        {
            Storage::disk('reviews')->fileExists($reviewImages[$i]->hashName());
        }
        $response->assertValid();

        $this->assertDatabaseHas('reviews', ['id' => Review::max('id')]);
    }

    public function testEditReview()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $user = User::factory()->has(Role::factory())->create();
        $productId = Seller::factory()->has(Product::factory())->create()->products()->first()->id;
        $review = Review::factory()->create();

        $response = $this->actingAs($user)->patch('/user/edit_review', [
            'review_id' => $review->id,
            'title' => 'New title',
            'pros' => 'New pros',
            'cons' => 'New cons',
            'comment' => 'New comment',
            'rating' => 10,
            'product_id' => $productId,
        ]);

        $response->assertValid();

        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'title' => 'New title',
            'pros' => 'New pros',
            'cons' => 'New cons',
            'comment' => 'New comment',
            'updated_at' => Carbon::now()->toDateTime()->format('Y-m-d H:i:s'),
            'rating' => 10,
        ]);
    }

    public function testDeleteReview()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $user = User::factory()->has(Role::factory())->create();
        Seller::factory()->has(Product::factory())->create();
        $review = Review::factory()->create();

        $this->actingAs($user)->delete('/user/delete_review', [
            'review_id' => $review->id,
        ]);

        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
    }
}
