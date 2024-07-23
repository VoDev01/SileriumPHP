<x-admin-layout>
    <x-slot name="title">
        Админ панель - Товары | Silerium
    </x-slot>
    <div class="d-flex">
        <form method="POST" action="/admin/products/post_product_search" style="width: 300px;">
            <h4>Поиск товаров</h4>
            @csrf
            <input hidden name="load_with" id="load_with" value="subcategory" />
            <input hidden name="redirect" id="redirect" value="products" />
            <div class="mb-3">
                <label for="name" class="form-label">Название товара</label>
                <input type="text" class="form-control" name="name" id="name" required/>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Продавец</label>
                <input type="text" class="form-control" name="seller" id="seller" required/>
            </div>
            <button type="submit" class="btn btn-primary" id="find_products">
                Найти
            </button>
        </form>
    </div>
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
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->description }}</td>
                        <td>{{ $product->priceRub }}</td>
                        <td>{{ $product->stockAmount }}</td>
                        <td>{{ $product->available }}</td>
                        <td>{{ $product->subcategory_id }}</td>
                        <td>{{ $product->timesPurchased }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <x-pagination :model="$products"/>
    </div>
</x-admin-layout>
