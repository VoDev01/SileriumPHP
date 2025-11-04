<?php

namespace Tests\Feature\Actions;

use Tests\TestCase;
use App\Models\Product;
use App\Actions\ManualPaginatorAction;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManualPaginatorActionTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testPaginate()
    {
        $products = Product::factory(15)->make();

        $this->assertTrue(
            ManualPaginatorAction::paginate($products->toArray(), 15, 1)->items()
                === (new LengthAwarePaginator($products->toArray(), count($products), 15, 1))->items()
        );

        $this->assertFalse(
            ManualPaginatorAction::paginate($products->toArray(), 10, 1)->items()
                === (new LengthAwarePaginator($products->toArray(), count($products), 15, 1))->items()
        );
    }
}
