<x-layout>
    <x-slot name="title">
        Оплата отклонена | Silerium
    </x-slot>
    <h1>Оплата отклонена!</h1>
    <p>Номер заказа - {{ $order->ulid }}</p>
    <br>
    <p>Инициатор отмены платежа: {{$cancellationParty}}</p>
    <p>Причина отмены платежа: {{$cancellationReason}}</p>
    <br>
    <p>Товары: </p>
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
    <p>Отклонен: {{ $order->deleted_at }}</p>
    @php
        $orderStatusDB = $order->orderStatus;
        $orderStatusStr = explode(',', App\Enum\OrderStatus::fromName($orderStatusDB)->value);
    @endphp
    <p style="color: {{ $orderStatusStr[App\Enum\color] }};">Статус заказа:
        {{ $orderStatusStr[App\Enum\ru] }}</p>
    <hr>
</x-layout>
