<x-seller-layout>
    <x-slot:title>
        Личный кабинет продавца | Silerium Partner
    </x-slot>
    <a href="/seller/edit_seller" class="text-decoration-none">Изменить данные продавца</a>
    <a href="/seller/products/index" class="text-decoration-none">Управление товарами</a>
    <a href="/seller/orders" class="text-decoration-none">Заказы</a>
    <a href="/seller/economical_reports" class="text-decoration-none">Экономическая отчетность</a>
    <form action="/seller/logout" method="POST">
        @csrf
        <button type="submit" class="btn btn-danger">Выйти</button>
    </form>
</x-seller-layout>