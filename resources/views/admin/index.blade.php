<x-admin-layout>
    <x-slot name="title">
        Админ панель - Главная | Silerium
    </x-slot>
    <div class="d-flex flex-column justify-content-center align-items-center h-100">
        <h1>Управление сайтом</h1>
        <div class="d-flex flex-column">
            <a class="btn" data-bs-toggle="collapse" href="#products" role="button" aria-expanded="false"
                aria-controls="products">
                Товары
            </a>
            <nav class="collapse" id="products">
                <div class="d-flex flex-column align-items-center">
                    <a class="text-decoration-none" href="/admin/products/index">Все</a>
                    <a class="text-decoration-none" href="/admin/products/update">Изменить</a>
                    <a class="text-decoration-none" href="/admin/products/delete">Удалить</a>
                    <a class="text-decoration-none" href="/admin/products/reviews">Отзывы</a>
                </div>
            </nav>
            <a class="btn" data-bs-toggle="collapse" data-bs-target="#users" href="#users" role="button"
                aria-expanded="false" aria-controls="users">
                Пользователи
            </a>
            <nav class="collapse" id="users">
                <div class="d-flex flex-column align-items-center">
                    <a class="text-decoration-none" href="/admin/users/index">Все</a>
                    <a class="text-decoration-none" href="/admin/users/orders">Заказы</a>
                    <a class="text-decoration-none" href="/admin/users/reviews">Отзывы</a>
                    <a class="text-decoration-none" href="/admin/users/roles">Роли</a>
                    <a class="text-decoration-none" href="/admin/users/payments">Платежи</a>
                    <a class="text-decoration-none" href="/admin/users/ban">Заблокировать</a>
                    <a class="text-decoration-none" href="/admin/users/delete">Удалить</a>
                </div>
            </nav>
            <a class="btn" data-bs-toggle="collapse" data-bs-target="#traffic" href="#traffic" role="button"
                aria-expanded="false" aria-controls="traffic">
                Трафик
            </a>
            <a class="btn" data-bs-toggle="collapse" data-bs-target="#storage" href="#storage" role="button"
                aria-expanded="false" aria-controls="storage">
                Хранилище
            </a>
            <nav class="collapse" id="storage">
                <div class="d-flex flex-column align-items-center">
                    <a class="text-decoration-none" href="/admin/control_auth_dropbox">Авторизация</a>
                </div>
            </nav>
        </div>
    </div>
</x-admin-layout>
