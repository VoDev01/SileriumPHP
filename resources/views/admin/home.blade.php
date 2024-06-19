<x-admin-layout>
    <x-slot name="title">
        Админ панель - Главная | Silerium
    </x-slot>
    <div class="container-md g-3">
        <h1 class="align-content-center">Управление сайтом</h1>
        <div class="row">

            <a class="btn col" data-bs-toggle="collapse" href="#content" role="button" aria-expanded="false" aria-controls="content">
                Товары
            </a>

            <nav>
                <a href="products/index">Все</a>
                <a href="products/create">Добавить</a>
                <a href="products/update?id=1">Изменить</a>
                <a href="products/delete?id=1">Удалить</a>
            </nav>

            <a class="btn col" data-bs-toggle="collapse" data-bs-target="#users" href="#users" role="button" aria-expanded="false" aria-controls="users">
                Пользователи
            </a>

            <nav>
                
            </nav>

            <a class="btn col" data-bs-toggle="collapse" data-bs-target="#reviews" href="#reviews" role="button" aria-expanded="false" aria-controls="reviews">
                Отзывы
            </a>

            <nav>
                
            </nav>

            <a class="btn col" data-bs-toggle="collapse" data-bs-target="#traffic" href="#traffic" role="button" aria-expanded="false" aria-controls="traffic">
                Трафик
            </a>
        </div>
    </div>
</x-admin-layout>