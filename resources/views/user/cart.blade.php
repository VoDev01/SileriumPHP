<x-layout>
    <x-slot name="title">
        Корзина | Silerium
    </x-slot>
    @section('scripts')
        <script type="text/javascript">
            function deliveryWarn() {
                toastr.options.preventDuplicates = true;
                toastr.warning('Заказ с данным статусом изменить или отозвать невозможно.');
                $('#edit_order').prop("href", "#");
                $('#delete_order').prop("disabled", true);
            }
            function increaseAmount()
            {
                $('#amount_change').val("up");
            }
            function decreaseAmount()
            {
                $('#amount_change').val("down");
            }
        </script>
    @endsection
    <div class="container-fluid mx-3 pt-3">
        <x-error style="font-size: 24px;" field="homeAdress" />
        @if ($products->count() != 0)
            @foreach ($products as $product)
                <div class="row" style="heigth: 100px; width: 800px;">
                    @php
                        $productImage = $product->images == null ? '' : $product->images->first()->imagePath;
                    @endphp
                    <img class="col" alt="картинка товара" width="100" height="100" src="{{ asset($productImage) }}">
                    <div class="row col flex-column">
                        <p class="col">{{ $product->name }}</p>
                        <p class="col text-secondary">{{ $product->quantity }} шт.</p>
                        <form action="/user/cart/removefromcart" method="POST">
                            @csrf
                            <input hidden name="productId" value="{{ $product->id }}">
                            <button type="submit" class="border-0 bg-white text-danger col">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                        {{-- @php
                                $orderStatusDB = $orderStatus;
                                $orderStatusStr = explode(',', App\Enum\OrderStatus::fromName($orderStatusDB)->value);
                            @endphp
                            <p style="color: {{ $orderStatusStr[App\Enum\color] }};">Статус заказа:
                                {{ $orderStatusStr[App\Enum\ru] }}</p>
                            @if ($orderStatus == 'DELIVERY')
                                <form action="/user/closeorder" method="POST">
                                    @csrf
                                    <input hidden name="order_id" value="{{ $id }}">
                                    <button type="submit" id="delete_order" class="btn btn-outline-danger" onclick="deliveryWarn()">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            @else
                                
                            @endif --}}
                    </div>
                    <div class="row col">
                        <form action="/user/cart/changeamount" class="form-inline" method="POST">
                            @csrf
                            <div class="btn-group">
                                <input hidden name="product_id" value="{{$product->id}}" />
                                <input hidden id="amountChange" name="amountChange"/>
                                <button type="submit" class="btn" onclick="decreaseAmount()">-</button>
                                <input class="form-control" style="width: 50px;" type="number" name="amount" id="amount" 
                                value="{{$product->quantity}}" onchange="this.form.submit()">
                                <button type="submit" class="btn" onclick="increaseAmount()">+</button>
                            </div>
                        </form>
                        <p class="col text-secondary">{{ $product->getPriceSum() }} &#8381;</p>
                    </div>
                    <hr />
                </div>
            @endforeach
            <a class="btn btn-outline-success" href="/user/checkoutorder">Оформить</a>
    </div>
    {{-- <div class="col-3 mt-1">
                    <h3>Фильтровать по статусу</h3>
                    <form action="/user/filtershopcart" method="POST">
                        @csrf
                        <select class="form-select mb-3" name="order_status" style="width: 250px;">
                            @php
                                $orderStatusDB = $orderStatus;
                                $orderStatusStr = explode(',', App\Enum\OrderStatus::fromName($orderStatusDB)->value);
                            @endphp
                            @foreach (App\Enum\OrderStatus::cases() as $status)
                                @if ($status === App\Enum\OrderStatus::CLOSED)
                                    @continue
                                @endif
                                <option value="{{ $status->value }}">{{ $orderStatusStr[App\Enum\ru] }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-outline-success">Фильтровать</button>
                        <a class="btn btn-outline-secondary" href="/user/ordershistory">История заказов</a>
                    </form>
                </div> --}}
@else
    <p>Корзина пуста.</p>
    <a class="btn btn-outline-primary" href="/categories/all">К категориям товаров</a>
    {{-- <div class="col-3 mt-1">
                    <h3>Фильтровать по статусу</h3>
                    <form action="/user/filtershopcart" method="POST">
                        @csrf
                        <select class="form-select mb-3" name="order_status" style="width: 250px;">
                            @php
                                $orderStatusDB = $orderStatus;
                                $orderStatusStr = explode(',', App\Enum\OrderStatus::fromName($orderStatusDB)->value);
                            @endphp
                            @foreach (App\Enum\OrderStatus::cases() as $status)
                                @if ($status === App\Enum\OrderStatus::CLOSED)
                                    @continue
                                @endif
                                <option value="{{ $status->value }}">{{ $orderStatusStr[App\Enum\ru] }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-outline-success">Фильтровать</button>
                        <a class="btn btn-outline-secondary" href="/user/ordershistory">История заказов</a>
                    </form>
                </div> --}}
    @endif
    </div>
    </div>
</x-layout>
