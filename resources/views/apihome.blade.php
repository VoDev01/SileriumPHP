<x-api-layout>
    <x-slot name="title">
        Главная | Silerium-API
    </x-slot>
    <div class="container">
        <h1>API</h1>
        <ul class="nav nav-pills">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                    aria-haspopup="true" aria-expanded="false">Товары</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="/api/v1/products/index">Все</a>
                    <a class="dropdown-item" href="#">Создать</a>
                    <a class="dropdown-item" href="#">Изменить</a>
                    <a class="dropdown-item" href="#">Удалить</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                    aria-haspopup="true" aria-expanded="false">Подкатегории</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="/api/v1/subcategories/index">Все</a>
                    <a class="dropdown-item" href="#">Создать</a>
                    <a class="dropdown-item" href="#">Изменить</a>
                    <a class="dropdown-item" href="#">Удалить</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                    aria-haspopup="true" aria-expanded="false">Категории</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="/api/v1/categories/index">Все</a>
                    <a class="dropdown-item" href="#">Создать</a>
                    <a class="dropdown-item" href="#">Изменить</a>
                    <a class="dropdown-item" href="#">Удалить</a>
                </div>
            </li>
        </ul>
    </div>
</x-api-layout>
