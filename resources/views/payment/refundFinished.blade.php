<x-layout>
    <x-slot name="title">
        Возврат завершен | Silerium
    </x-slot>
    <div class="container">
        <h1>Возврат завершен!</h1>
        <p>Номер заказа - {{ $order->ulid }}</p>
        <p>Товары: </p>
        <div class="scroll-container">
            @foreach ($order->products as $product)
                @if ($product->images->first() != null)
                    <img width="128" height="128" src="{{ $product->images->first()->imagePath }}"
                        alt="картинка товара {{ $product->name }}" />
                    <span class="text-dark">{{ $product->name }}</span>
                @else
                    <img width="128" height="128" alt="картинка товара {{ $product->name }}" />
                    <span class="text-dark">{{ $product->name }}</span>
                @endif
            @endforeach
        </div>
        <p>Цена итого: {{ $order->totalPrice }} &#8381;</p>
        <p>Оформлен: {{ $order->created_at }}</p>
        @php
            $statusDB = $order->status;
            $statusStr = explode(',', App\Enum\OrderStatus::fromName($statusDB)->value);
        @endphp
        <p style="color: {{ $statusStr[App\Enum\color] }};">Статус заказа:
            {{ $statusStr[App\Enum\ru] }}</p>
        <a class="btn btn-primary text-decoration-none" href="/categories/all">К категориям</a>
    </div>
</x-layout>
