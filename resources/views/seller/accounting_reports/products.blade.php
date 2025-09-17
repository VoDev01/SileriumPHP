<x-seller-layout>
    <x-slot name="title">
        Отчетность товаров | Silerium Partner
    </x-slot>
    <link rel="stylesheet" type="text/css" href="{{asset('css/pdf-style.css')}}"/>
    <h1>Отчености товаров</h1>
    <x-search-form header="Поиск товаров" submit_id="searchProducts" :$queryInputs :$inputs :$hiddenInputs />
    @if ($products != null)
        <div id="seller-products">
            <table>
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Id</th>
                        <th>Название товара</th>
                        <th>Кол-во.</th>
                        <th>Ед.</th>
                        <th>Цена</th>
                        <th>Валюта</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 0;
                    @endphp
                    @foreach ($products as $product)
                        <tr>
                            @php
                                $i++;
                                $product = (object) $product;
                            @endphp
                            <td>{{ $i }}</td>
                            <td>{{ $product->ulid }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->productAmount }}</td>
                            <td>Шт.</td>
                            <td>{{ $product->priceRub }}</td>
                            <td>Руб.</td>
                            <td><a class="text-decoration-none btn btn-primary"
                                    href="/seller/accounting_reports/product/{{ $product->ulid }}">Отчет</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <x-pagination :model="$products" />
        </div>
    @endif
</x-seller-layout>
