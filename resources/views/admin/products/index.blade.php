<x-admin-layout>
    <x-slot name="title">
        Админ панель - Товары | Silerium
    </x-slot>
    <div class="container">
        <x-search-form header="Поиск товаров" submit_id="find_products" :$queryInputs :$inputs />
        <h1 class="text-center">Товары</h1>
        <div class="container-fluid">
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
                        @php
                            $product = (object)$product;
                        @endphp
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
            <x-pagination :model="$products" :params="['searchKey' => session('searchKey')]"/>
        </div>
    </div>
</x-admin-layout>
