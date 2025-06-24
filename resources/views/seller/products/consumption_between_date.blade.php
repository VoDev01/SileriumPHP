<x-seller-layout>
    <x-slot name="title">
        Товары | Silerium Partner
    </x-slot>
    <x-search-form header="Поиск товаров" submitId="find-products" :$queryInputs :$inputs />
    @if ($amountExpiry != null)
        <h1 class="text-center">Потребление товаров в определенное время</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td>Id товара</td>
                    <td>Название</td>
                    <td>Израсходуется через месяцев</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($amountExpiry as $expiry)
                    <tr>
                        @php
                            $expiry = (object)$expiry;
                        @endphp
                        <td>{{ $expiry->product_id }}</td>
                        <td>{{ $expiry->prodName }}</td>
                        <td>{{ $expiry->est_expiry_time }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <x-pagination :model="$expiry" />
    @else
        <span>{{$message}}</span>
    @endif
</x-seller-layout>