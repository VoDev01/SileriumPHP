<x-seller-layout>
    <x-slot:title>
        Личный кабинет продавца | Silerium Partner
    </x-slot>
    <h1>Личный кабинет</h1>
    <div class="border-bottom border-dark">
        <div class="mx-5">
            <img src="{{ asset($seller->logo) }}" alt="Лого продавца" width="128" height="128" />
            <h3>{{ $seller->nickname }}</h3>
            <p>{{ $seller->organization_name }}</p>
            <p>{{ $seller->organization_email }}</p>
        </div>
    </div>
    <div class="pb-2 d-flex flex-column align-items-start">
        <a href="/seller/account/edit" class="text-decoration-none text-dark btn">Изменить данные продавца</a>

        <button class="btn" type="button" data-bs-toggle="collapse" data-bs-target="#products" aria-expanded="false"
            aria-controls="products">
            Управление товарами
        </button>

        <div class="collapse" id="products">
            <div class="mx-5 d-flex flex-column">
                <a class="text-decoration-none text-dark" href="/seller/products/list">База товаров</a>
                <a class="text-decoration-none text-dark" href="/seller/products/create">Добавить</a>
                <a class="text-decoration-none text-dark" href="/seller/products/update">Изменить</a>
                <a class="text-decoration-none text-dark" href="/seller/products/delete">Удалить</a>
                <a class="text-decoration-none text-dark" href="/seller/products/reviews">Отзывы</a>
            </div>
        </div>

        <a href="/seller/orders/list" class="text-decoration-none text-dark btn">Заказы</a>

        <button class="btn" type="button" data-bs-toggle="collapse" data-bs-target="#accounting_reports"
            aria-expanded="false" aria-controls="accounting_reports">
            Бухгалтерская отчетность
        </button>

        <div class="collapse" id="accounting_reports">
            <div class="mx-5 d-flex flex-column">
                <a class="text-decoration-none text-dark" href="/seller/accounting_reports/generic">Общая отченость</a>
                <a class="text-decoration-none text-dark" href="/seller/accounting_reports/payments">Отченость о
                    доходах</a>
                <a class="text-decoration-none text-dark" href="/seller/accounting_reports/refunds">Отченость о
                    возвратах</a>
                <a class="text-decoration-none text-dark" href="/seller/accounting_reports/products">Отчетность товаров</a>
                <a class="text-decoration-none text-dark" href="/seller/accounting_reports/tax">Отченость для
                    представления
                    в
                    налоговые органы</a>
            </div>
        </div>
    </div>
    <form action="/seller/logout" method="POST">
        @csrf
        <button type="submit" class="btn btn-danger">Выйти</button>
    </form>
</x-seller-layout>
