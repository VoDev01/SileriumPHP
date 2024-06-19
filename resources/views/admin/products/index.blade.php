<x-admin-layout>
    <x-slot name="title">
        Админ панель - Товары | Silerium
    </x-slot>
    <h1 class="text-center">Товары</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <td>Id</td>
                <td>Имя</td>
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
                    <td>{{$product->id}}</td>
                    <td>{{$product->name}}</td>
                    <td>{{$product->description}}</td>
                    <td>{{$product->priceRub}}</td>
                    <td>{{$product->stockAmount}}</td>
                    <td>{{$product->available}}</td>
                    <td>{{$subcategories->where('id', $product->subcategory_id)->first()->name}}</td>
                    <td>{{$product->timesPurchased}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-admin-layout>