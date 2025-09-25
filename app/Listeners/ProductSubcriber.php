<?php

namespace App\Listeners;

use App\Events\Product\ProductBoughtEvent;
use App\Events\Product\ProductReviewedEvent;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\InteractsWithQueue;

class ProductSubcriber
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handleProductBought(ProductBoughtEvent $event)
    {
        Product::where('ulid', $event->product->ulid)->update(['productAmount' => $event->product->productAmount - $event->amount]);
    }

    public function handleProductReviewed(ProductReviewedEvent $event)
    {

    }

    public function subscribe(Dispatcher $events)
    {
        $events->listen(ProductBoughtEvent::class, [ProductSubcriber::class, 'handleProductBought']);
        $events->listen(ProductReviewedEvent::class, [ProductSubcriber::class, 'handleProductReviewed']);
    }
}
