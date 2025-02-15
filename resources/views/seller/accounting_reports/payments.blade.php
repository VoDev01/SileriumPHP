<x-seller-layout>
    <x-slot name="title">
        Отчет о доходах | Silerium Partner
    </x-slot>
    <div class="container">
        <h1>Отчет о доходах с продажи товаров</h1>
        <x-search-form header="Поиск платежей" submit_id="search_payments" :$inputs :$queryInputs />
        <h3>Платежи</h3>
        @foreach ($payments as $payment)
            <div class="row">
                <div class="col-4">
                    <p>{{ $payment->order->user->name }} {{ $payment->order->user->surname }}</p>
                    <p>Заказ {{ $payment->order->ulid }}</p>
                </div>
                <div class="col-4">
                    <p>{{ $payment->status }}</p>
                    <p>{{ $payment->payment_id }}</p>
                    <p>{{ $payment->created_at }}</p>
                </div>
                <div class="col-4 align-content-center">
                    <p style="font-size: 26px; font-weight:500;">{{ $payment->order->totalPrice }} руб</p>
                </div>
            </div>
            <hr>
        @endforeach
        <x-pagination :model="$payments" />
    </div>
</x-seller-layout>
