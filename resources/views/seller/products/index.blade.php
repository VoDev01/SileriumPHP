<x-seller-layout>
    <x-slot name="title">
        Товары | Silerium Partner
    </x-slot>
    @php
        $inputs = array(
            array('inputName' => 'product_name', 'displayName' => 'Название товара', 'field' = 'product_name', 'required' => true),
            array('inputName' => 'seller_name', 'displayName' => 'Название продавца', 'field' = 'seller_name', 'required' => true)
        );
    @endphp
    <x-search-form action-url="/seller/products/products_search" loadWith="subcategory" redirect="products" header="Поиск товаров" submit_id="find_products" :$inputs>
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
        <x-pagination :model="$products"/>
</x-seller-layout>
