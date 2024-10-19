<x-layout>
    <x-slot name="title">
        История заказов | Silerium
    </x-slot>
    <div class="mx-3 pt-3">
        @if ($orders->count() == 0)
            <h3>У вас нету закрытых заказов.</h3>
        @else
            @foreach ($orders as $order)
                <p>Номер заказа - {{ $order->id }}</p>
                <p>Товары</p>
                <div class="scroll-container">
                    @foreach ($order->products as $product)
                        <img width="128" height="128" src="{{asset($product->images->first()->imagePath)}}" alt="картинка товара {{$product->name}}"/>
                        <span class="text-dark">{{$product->name}}</span>
                    @endforeach
                </div>
                <p>Цена итого: {{ $order->totalPrice }} &#8381;</p>
                <p>Оформлен: {{$order->orderDate}}</p>
                @php
                    $orderStatusDB = $order->orderStatus;
                    $orderStatusStr = explode(',', App\Enum\OrderStatus::fromName($orderStatusDB)->value);
                @endphp
                <p style="color: {{$orderStatusStr[App\Enum\color]}};">Статус заказа: {{$orderStatusStr[App\Enum\ru]}}</p>
                <hr />
            @endforeach
        @endif
        <a class="btn btn-outline-success" href="/user/cart">К заказам</a>
    </div>
</x-layout>
