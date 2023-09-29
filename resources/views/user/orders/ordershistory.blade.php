<x-layout>
    <x-slot name="title">
        История заказов
    </x-slot>
    <div class="mx-3 pt-3">
        @if ($orders->count() == 0)
            <h3>У вас нету закрытых заказов.</h3>
        @else
            @foreach ($orders as $order)
                <p>Номер заказа - {{ $order->id }}</p>
                <img alt="картинка товара" width="250" height="250"
                    src="{{ asset($order->product->images->first()) }}">
                <p>Цена итого: {{ $order->totalPrice }} &#8381;</p>
                <p>Составлен: {{$order->orderDate}}</p>
                @php
                    $orderStatusDB = $order->orderStatus;
                    $orderStatusStr = explode(',', App\Enum\OrderStatus::fromName($orderStatusDB)->value);
                @endphp
                <p style="color: {{$orderStatusStr[App\Enum\color]}};">Статус заказа: {{$orderStatusStr[App\Enum\ru]}}</p>
                <hr />
            @endforeach
        @endif
        <a class="btn btn-outline-success" href="/user/shopcart">К заказам</a>
    </div>
</x-layout>
