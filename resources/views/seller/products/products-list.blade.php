<x-seller-layout>
    <x-slot name="title">
        Товары | Silerium Partner
    </x-slot>
    <x-search-form header="Поиск товаров" submitId="find-products" :$queryInputs :$inputs />
    @if ($products != null)
        <h1 class="text-center">Ваши товары</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td>Id</td>
                    <td>Название</td>
                    <td>Описание</td>
                    <td>Цена в рублях</td>
                    <td>Количество</td>
                    <td>В продаже</td>
                    <td>Подкатегория</td>
                    <td>Куплено раз</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        @php
                            $product = (object)$product;
                        @endphp
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->description }}</td>
                        <td>{{ $product->priceRub }}</td>
                        <td>{{ $product->productAmount }}</td>
                        <td>{{ $product->available }}</td>
                        <td>{{ $product->subcategory_id }}</td>
                        <td>{{ $product->timesPurchased }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <x-pagination :model="$products" />
    @else
        <span>{{$message}}</span>
    @endif
</x-seller-layout>
