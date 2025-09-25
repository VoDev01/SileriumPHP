<?php

namespace App\Listeners;

use App\Events\Order\OrderStatusEvent;
use App\Notifications\statusChangedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class OrderSubcriber
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
    public function handlestatusEvent(OrderStatusEvent $event)
    {
        $event->order->user->notify(new statusChangedNotification($event->order));
    }

    public function subscribe(Dispatcher $events)
    {
        $events->listen(OrderStatusEvent::class, [OrderSubcriber::class, 'handlestatusEvent']);
    }
    
}
