<x-seller-layout>
    <x-slot name="title">
        Отченость товара | Silerium Partner
    </x-slot>
    <h3>Отчет о розничных продажах товара {{$product->ulid}} от 01-2025</h3>
    <div class="row" style="width: 750px;">
        <div class="col-6"><p>Id: {{$product->ulid}}</p></div>
        <div class="col-6"><p>Название: {{$product->name}}</p></div>
        <div class="col-6"><p>Количество на складе: </p></div>
        <div class="col-6"><p>Количество продаж: </p></div>
        <div class="col-6"><p>Выручка: </p></div>
        <div class="col-6"><p>Расходы: </p></div>
        <div class="col-6"><p>Доходы: </p></div>
    </div>
</x-seller-layout>