<x-layout>
    <x-slot name="title">
        Добавление товара в корзину | Silerium
    </x-slot>

    @section('scripts')
        <script type="module">
            let basePrice =
                $("#amount").change(function() {
                    let amount = $(this).val();
                    let basePrice = $("#basePrice").val();
                    let totalPrice = (basePrice * amount).toFixed(1);
                    console.log(totalPrice);
                    let strPrice = new Intl.NumberFormat("ru", {
                        minimumFractionDigits: 1
                    }).format(totalPrice);
                    console.log(strPrice)
                    $("#price").text(strPrice);
                });
        </script>
    @endsection

    <div class="container" style="width: 650px;">
        <h1>{{ $product->name }}</h1>
        <img alt="картинка товара" src="{{ asset($product->images->first()) }}" />
        <form action="/user/cart/add_to_cart" method="POST">
            @csrf
            <div class="row">
                <div class="col-6">
                    <label for="amount" class="form-label">Количество шт.</label>
                </div>
                <div class="col-6">
                    <input type="number" name="amount" id="amount" class="form-control" value="1"
                        style="width: 100px;" />
                </div>
            </div>
            <input hidden id="basePrice" value="{{ $product->priceRub }}" />
            <input hidden name="productId" value="{{ $product->id }}" />
            <h3>Цена итого: <span id="price">{{ $product->priceRub }}</span> &#8381</h3>
            <button type="submit" class="btn btn-success text-white">В корзину</button>
        </form>
    </div>
</x-layout>
