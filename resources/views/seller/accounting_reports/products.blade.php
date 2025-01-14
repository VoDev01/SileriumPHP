<x-seller-layout>
    <x-slot name="title">
        Отчетность товаров | Silerium Partner
    </x-slot>
    <h1>Бухгалтерские отчености товаров</h1>
    <x-search-form header="Поиск товаров" submit_id="searchProducts" :$queryInputs :$inputs />
    @if ($products != null)
        <div class="table-responsive">
            <table class="table table-bordered" style="border: black;">
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Id</th>
                        <th>Название товара</th>
                        <th>Кол-во.</th>
                        <th>Ед.</th>
                        <th>Цена</th>
                        <th>Сумма</th>
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
                            <td>Ед.</td>
                            <td>{{ $product->priceRub }}</td>
                            <td>{{ $product->priceRub * $product->productAmount }}</td>
                            <td><a class="text-decoration-none btn btn-primary"
                                    href="/seller/accounting_reports/product/{{ $product->ulid }}">Отчет</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <x-pagination :model="$products" />
        </div>
    @endif
    <form action="/seller/accounting_reports/format/pdf" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">
            Форматировать
        </button>
    </form>
</x-seller-layout>
