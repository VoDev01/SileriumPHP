<x-seller-layout>
    <x-slot name="title">
        Заказы | Silerium Parter
    </x-slot>
    <x-search-form header="Поиск заказов на товары" submitId="find-products" :$queryInputs :$inputs />
    @if ($orders !== null)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td>Номер</td>
                    <td>Название(я) товара(ов)</td>
                    <td>Количество</td>
                    <td>Руб.</td>
                    <td>Дата оформления</td>
                    <td>Адрес доставки</td>
                    <td>Статус</td>
                    <td>Контактные данные</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    @php
                        $order = (object) $order;
                        $order->user = (object) $order->user;
                    @endphp
                    <tr>
                        <td>{{ $order->ulid }}</td>
                        <td>{{ $productsNames }}</td>
                        <td>{{ $productsAmounts }}</td>
                        <td>{{ $order->totalPrice }}</td>
                        <td>{{ $order->created_at }}</td>
                        <td>{{ $order->address }}</td>
                        @php
                            $statusDB = $order->status;
                            $statusStr = explode(',', App\Enum\status::fromName($statusDB)->value);
                        @endphp
                        <td>{{ $statusStr[\App\Enum\ru] }}</td>
                        <td>{{ $order->user->phone . ' ' . $order->user->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <x-pagination :model="$orders" />
    @endif
</x-seller-layout>
