<x-admin-layout>
    <x-slot name="title">
        Админ панель - Отзывы товара | Silerium
    </x-slot>
    <div class="container">
        <x-api-search-form action-url="/admin/products/post_product_search" load-with="reviews" redirect="product_reviews" header="Поиск товара">
            <div class="mb-3">
                <label for="name" class="form-label">Название товара</label>
                <input type="text" class="form-control" name="name" id="name" required />
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Продавец</label>
                <input type="text" class="form-control" name="seller" id="seller" required />
            </div>
            <button type="submit" class="btn btn-primary" id="find_reviews">
                Найти
            </button>
        </x-api-search-form>

        @if ($productInfoReceived)
            <div class="container border border-dark" id="product_reviews">
                <h5>{{ $user['name'] }} {{ $user['surname'] }}</h5>
                <img src="{{ $user['profilePicture'] }}" alt="Аватарка пользователя"
                    style="width: 128px; heigth: 128px;" />
                <p>{{ $user['email'] }}</p>
                <p>{{ $user['country'] }}</p>
                <p>{{ $user['city'] }}</p>
                <p>{{ $user['homeAdress'] }}</p>

                <th></th>

                <h5>Отзывы пользователя {{ $user['name'] }} {{ $user['surname'] }}</h5>
                @foreach ($user['reviews'] as $review)
                    <p>Название отзыва: {{ $review['title'] }}</p>
                    <p>Плюсы: {{ $review['pros'] }}</p>
                    <p>Минусы: {{ $review['cons'] }}</p>
                    <p>Комментарий: {{ $review['comment'] }}</p>
                    <p>Оценка: {{ $review['rating'] }}</p>
                    <p>Создан: {{ $review['createdAt'] }}</p>
                    <p>Изменен: {{ $review['updatedAt'] }}</p>
                    <p>Отзыв на товар: {{ $review['product']['name'] }}</p>
                @endforeach
                <x-pagination :model="$userPaginatedReviews" />
            </div>
        @endif
    </div>
</x-admin-layout>
