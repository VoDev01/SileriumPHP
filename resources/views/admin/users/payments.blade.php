<x-admin-layout>
    <x-slot name="title">
        Платежи | Silerium
    </x-slot>
    <!--<div class="d-flex flex-column justify-content-center align-items-center h-100">-->
        <x-search-form header="Поиск платежей" submit_id="search_payments" :$inputs :$queryInputs />
        <div>
            <h3>Платежи</h3>
        </div>
        @foreach ($payments as $payment)
            <div class="row">
                <div class="col-4">
                    <p>{{ $payment->order->user->name }} {{ $payment->order->user->surname }}</p>
                    <p>Заказ {{ $payment->order->ulid }}</p>
                </div>
                <div class="col-4">
                    <p>Статус: {{ $payment->status }}</p>
                    <p style="word-break: break-all;">Id платежа: {{ $payment->payment_id }}</p>
                    <p>{{ $payment->created_at }}</p>
                </div>
                <div class="col-4 align-content-center">
                    <p style="font-size: 26px; font-weight:500;">{{ $payment->order->totalPrice }} руб</p>
                </div>
            </div>
            <hr>
        @endforeach
        <x-pagination :model="$payments" />
    <!--</div>-->
</x-admin-layout>
