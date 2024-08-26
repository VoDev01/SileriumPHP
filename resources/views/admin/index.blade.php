<x-admin-layout>
    <x-slot name="title">
        Админ панель - Главная | Silerium
    </x-slot>
    <div class="container-md g-3">
        <h1 class="align-content-center">Управление сайтом</h1>
        <div class="row">
            <div class="col">
                <a class="btn" data-bs-toggle="collapse" href="#content" role="button" aria-expanded="false"
                    aria-controls="content">
                    Товары
                </a>

                <nav>
                    <a class="text-decoration-none" href="/admin/products/index">Все</a>
                    <a class="text-decoration-none" href="/admin/products/create">Добавить</a>
                    <a class="text-decoration-none" href="/admin/products/update/1">Изменить</a>
                    <a class="text-decoration-none" href="/admin/products/delete/1">Удалить</a>
                    <a class="text-decoration-none" href="/admin/products/find/reviews">Отзывы</a>
                </nav>
            </div>
            <div class="col"> 
                <a class="btn" data-bs-toggle="collapse" data-bs-target="#users" href="#users" role="button"
                    aria-expanded="false" aria-controls="users">
                    Пользователи
                </a>

                <nav>
                    <a class="text-decoration-none" href="/admin/users/index">Все</a>
                    <a class="text-decoration-none" href="/admin/users/orders">Заказы</a>
                    <a class="text-decoration-none" href="/admin/users/reviews">Отзывы</a>
                    <a class="text-decoration-none" href="/admin/users/roles">Роли</a>
                    <a class="text-decoration-none" href="/admin/users/ban/1">Заблокировать</a>
                    <a class="text-decoration-none" href="/admin/users/delete/1">Удалить</a>
                </nav>

            </div>
            <div class="col">
                <a class="btn" data-bs-toggle="collapse" data-bs-target="#traffic" href="#traffic" role="button"
                    aria-expanded="false" aria-controls="traffic">
                    Трафик
                </a>
            </div>
        </div>
    </div>
</x-admin-layout>
