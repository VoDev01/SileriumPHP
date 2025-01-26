<x-admin-layout>
    <x-slot name="title">
        Админ панель - Главная | Silerium
    </x-slot>
    <div class="container-md g-3">
        <h1 class="align-content-center">Управление сайтом</h1>
        <a class="btn" data-bs-toggle="collapse" href="#products" role="button" aria-expanded="false"
            aria-controls="products">
            Товары
        </a>
        <br>
        <nav class="collapse" id="products">
            <a class="text-decoration-none" href="/admin/products/index">Все</a>
            <a class="text-decoration-none" href="/admin/products/update">Изменить</a>
            <a class="text-decoration-none" href="/admin/products/delete">Удалить</a>
            <a class="text-decoration-none" href="/admin/products/reviews">Отзывы</a>
        </nav>
        <a class="btn" data-bs-toggle="collapse" data-bs-target="#users" href="#users" role="button"
            aria-expanded="false" aria-controls="users">
            Пользователи
        </a>
        <nav class="collapse" id="users">
            <a class="text-decoration-none" href="/admin/users/index">Все</a>
            <a class="text-decoration-none" href="/admin/users/orders">Заказы</a>
            <a class="text-decoration-none" href="/admin/users/reviews">Отзывы</a>
            <a class="text-decoration-none" href="/admin/users/roles">Роли</a>
            <a class="text-decoration-none" href="/admin/users/ban">Заблокировать</a>
            <a class="text-decoration-none" href="/admin/users/delete">Удалить</a>
        </nav>
        <br>
        <a class="btn" data-bs-toggle="collapse" data-bs-target="#traffic" href="#traffic" role="button"
            aria-expanded="false" aria-controls="traffic">
            Трафик
        </a>
    </div>
</x-admin-layout>
