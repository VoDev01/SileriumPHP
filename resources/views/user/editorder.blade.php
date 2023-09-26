<x-layout>
    <x-slot name="title">
        Редактирование заказа
    </x-slot>
    <script type="text/javascript">
        var basePrice =
            $("#amount").change(function() {
                var amount = $(this).val();
                var basePrice = $("#base_price").val();
                var totalPrice = (basePrice * amount).toFixed(2);
                console.log(totalPrice);
                var strPrice = new Intl.NumberFormat("ru").format(totalPrice);
                console.log(strPrice)
                $("#price").text(strPrice);
            });
    </script>
    <div class="container mobile-margin" style="width: 500px;">
        <h1>{{ $order->product->name }}</h1>
        <p>Номер заказа - {{ $order->id }}</p>
        <img alt="картинка товара" src="{{ asset($order->product->images->first()) }}" />
        <form action="/user/posteditorder" method="POST">
            <div class="row">
                <div class="col-2">
                    <label for="amount" class="form-label">Количество шт.</label>
                </div>
                <div class="col-2">
                    <input type="number" name="amount" id="amount" class="form-control" value="1"
                        style="width: 100px;" />
                </div>
                <div class="col-8"></div>
            </div>
            <input hidden id="base_price" value="{{$order->product->priceRub}}" />
            <input hidden name="order_id" value="{{$order->id}}" />
            <h3>Цена итого: <span id="price">{{$order->product->priceRub}}</span> &#8381</h3>
            <button type="submit" class="btn btn-success text-white">Изменить</button>
        </form>
    </div>
</x-layout>
