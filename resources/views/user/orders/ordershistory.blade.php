<x-layout>
    <x-slot name="title">
        История заказов | Silerium
    </x-slot>
    <div class="container">
        <h1>История заказов</h1>
        @if ($orders->count() == 0)
            <h3>У вас нету истории заказов.</h3>
        @else
            @foreach ($orders as $order)
                <p>Номер заказа - {{ $order->ulid }}</p>
                <p>Товары</p>
                <div class="scroll-container">
                    @foreach ($order->products as $product)
                        @if ($product->images->first() != null)
                            <img width="128" height="128" src="{{ asset($product->images->first()->imagePath) }}"
                                alt="картинка товара {{ $product->name }}" />
                            <span class="text-dark">{{ $product->name }}</span>
                        @else
                            <img width="128" height="128" alt="картинка товара {{ $product->name }}" />
                            <span class="text-dark">{{ $product->name }}</span>
                        @endif
                    @endforeach
                </div>
                <p>Цена итого: {{ $order->totalPrice }} &#8381;</p>
                <p>Оформлен: {{ $order->orderDate }}</p>
                @php
                    $orderStatusDB = $order->orderStatus;
                    $orderStatusStr = explode(',', App\Enum\OrderStatus::fromName($orderStatusDB)->value);
                @endphp
                <p style="color: {{ $orderStatusStr[App\Enum\color] }};">Статус заказа:
                    {{ $orderStatusStr[App\Enum\ru] }}</p>
                @if ($orderStatusStr[App\Enum\ru] == ' Обрабатывается')
                    <a class="btn btn-danger text-decoration-none"
                        href="/user/orders/refund?orderId={{ $order->ulid }}&paymentId={{$order->payment->payment_id}}">Отменить заказ</a>
                @elseif($orderStatusStr[App\Enum\ru] == ' Получен' && \Carbon\Carbon::now()->diffInDays($order->orderDate) >= 7)
                    <a class="btn btn-danger text-decoration-none"
                        href="/user/orders/refund?orderId={{ $order->ulid }}&paymentId={{$order->payment->payment_id}}">Вернуть средства</a>
                @else
                    <a class="btn text-decoration-none disabled" href="#"
                        style="background-color: black; color: white;">Отменить заказ</a>
                @endif
                <hr>
            @endforeach
        @endif
        <a class="btn btn-outline-success" href="/user/cart">К корзине</a>
    </div>
</x-layout>
