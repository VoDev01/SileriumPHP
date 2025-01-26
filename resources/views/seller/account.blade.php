<x-seller-layout>
    <x-slot:title>
        Личный кабинет продавца | Silerium Partner
    </x-slot>
    <a href="/seller/edit_seller" class="text-decoration-none">Изменить данные продавца</a>
    <a href="/seller/products/index" class="text-decoration-none">Управление товарами</a>
    <a href="/seller/orders/list" class="text-decoration-none">Заказы</a>
    <a href="/seller/accounting_reports/index" class="text-decoration-none">Бухгалтерская отчетность</a>
    <form action="/seller/logout" method="POST">
        @csrf
        <button type="submit" class="btn btn-danger">Выйти</button>
    </form>
</x-seller-layout>