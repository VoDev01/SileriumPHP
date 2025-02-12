<x-layout>
    <x-slot name="title">
        Оформление возврата | Silerium
    </x-slot>
    <div class="container">
        <h2>Возврат заказа {{ $order->ulid }}</h2>
        <form action="/user/orders/refund" method="POST">
            <p>
                <button class="btn btn-primary" type="button" data-bs-toggle="collapse"
                    data-bs-target="#products_to_refund" aria-expanded="false" aria-controls="products_to_refund">
                    Выберите товары которые вы хотите вернуть
                </button>
            </p>
            <div class="collapse" id="products_to_refund">
                @foreach ($order->products as $i => $product)
                    <p>{{$i}}. {{$product->name}}</p>
                    <img src="{{$product->images->first()->imagePath}}" width="150" height="150" alt="картика товара {{$product->name}}">
                @endforeach
            </div>
            <div class="btn-group" role="group" aria-label="Причина возврата">
                <input type="radio" class="btn-check" name="defective_product" id="btncheck1" autocomplete="off" />
                <label class="btn btn-outline-primary" for="btncheck1">Товар ненадлежащего качества</label>

                <input type="radio" class="btn-check" name="wrong_product" id="btncheck2" autocomplete="off" />
                <label class="btn btn-outline-primary" for="btncheck2">Пришёл не тот товар</label>

                <input type="radio" class="btn-check" name="product_specs_not_met" id="btncheck3"
                    autocomplete="off" />
                <label class="btn btn-outline-primary" for="btncheck3">Товар не соотвествует завленным
                    характеристикам</label>
            </div>

        </form>
    </div>
</x-layout>
