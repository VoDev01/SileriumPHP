<x-seller-layout>
    <x-slot name="title">
        Заказы | Silerium Parter
    </x-slot>
    <table class="table table-bordered">
        <thead>
            <tr>
                <td>Номер</td>
                <td>Руб.</td>
                <td>Дата оформления</td>
                <td>Адрес доставки</td>
                <td>Статус</td>
                <td>Контактные данные</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>{{$order->ulid}}</td>
                    <td>{{$order->totalPrice}}</td>
                    <td>{{$order->orderDate}}</td>
                    <td>{{$order->orderAdress}}</td>
                    <td>{{$order->orderStatus}}</td>
                    <td>{{$order->user->phone . " " . $order->user->name}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-seller-layout>