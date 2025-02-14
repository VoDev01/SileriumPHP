<x-layout>
    <x-slot name="title">
        Оформление возврата | Silerium
    </x-slot>
    <div class="container">
        <h2>Возврат заказа {{ $order->ulid }}</h2>
        <form action="/payment/refund" method="POST">
            @csrf
            <input hidden name="paymentId" value="{{$paymentId}}" >
            <p>
                <button class="btn" type="button" data-bs-toggle="collapse" data-bs-target="#products_to_refund"
                    aria-expanded="false" aria-controls="products_to_refund">
                    Выберите товары которые вы хотите вернуть
                </button>
            </p>
            <div class="collapse mb-3" id="products_to_refund">
            @foreach ($order->products as $i => $product)
                    <div class="mb-3">
                        <label for="product{{$i}}">{{ $i }}. {{ $product->name }}</label>
                        <img src="{{ $product->images->first()->imagePath ?? '' }}" width="150" height="150"
                            alt="картика товара {{ $product->name }}">
                        <input type="checkbox" name="products[]" id="product{{$i}}" value="{{$product->ulid}}" autocomplete="off">
                    </div>
                @endforeach
            </div>
            <div class="mb-3">
                <h3>Выберите причину возврата</h3>
                <div class="form-check">
                    <label for="refund_reason1">Товар ненадлежащего качества</label>
                    <input type="radio" name="refund_reason" id="refund_reason1" value="1" autocomplete="off" />
                </div>
                <div class="form-check">
                    <label for="refund_reason2">Пришёл не тот товар</label>
                    <input type="radio" name="refund_reason" id="refund_reason2" value="2" autocomplete="off" />
                </div>
                <div class="form-check">
                    <label for="refund_reason3">Товар не соотвествует завленным
                        характеристикам</label>
                    <input type="radio" name="refund_reason" id="refund_reason3" value="3" autocomplete="off" />
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Отправить</button>
        </form>
    </div>
</x-layout>
