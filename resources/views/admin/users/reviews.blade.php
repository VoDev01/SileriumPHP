<x-admin-layout>
    <x-slot name="title">
        Админ панель - Отзывы пользователя | Silerium
    </x-slot>
    <div class="container">
        <x-api-search-form actionUrl="/admin/users/post_user_search" loadWith="reviews" redirect="user_reviews"
            header="Поиск пользователей">
            <div class="mb-3">
                <label for="name" class="form-label">Имя</label>
                <input type="text" class="form-control" name="name" id="name" />
            </div>
            <div class="mb-3">
                <label for="surname" class="form-label">Фамилия</label>
                <input type="text" class="form-control" name="surname" id="surname" />
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" id="email" />
            </div>
            <button type="submit" class="btn btn-primary" id="find_user">
                Найти
            </button>
        </x-api-search-form>

        @if ($userInfoReceived)
            <div class="container" id="user_reviews">
                <div class="row mb-3 pb-3 border-bottom border-dark">
                    <div class="col-3">
                        <h5>{{ $user['name'] }} {{ $user['surname'] }}</h5>
                        <img src="{{ $user['profilePicture'] }}" alt="Аватарка пользователя"
                            style="width: 128px; heigth: 128px;" />
                    </div>

                    <div class="col-9">
                        <p>{{ $user['email'] }}</p>
                        <p>{{ $user['country'] }}</p>
                        <p>{{ $user['city'] }}</p>
                        <p>{{ $user['homeAdress'] }}</p>
                    </div>
                </div>

                <h5>Отзывы пользователя {{ $user['name'] }} {{ $user['surname'] }}</h5>
                <ol>
                    @foreach ($user['reviews'] as $review)
                        <li>
                            <p>Название отзыва: {{ $review['title'] }}</p>
                            <p>Плюсы: {{ $review['pros'] }}</p>
                            <p>Минусы: {{ $review['cons'] }}</p>
                            <p>Комментарий: {{ $review['comment'] }}</p>
                            <p>Оценка: {{ $review['rating'] }}</p>
                            <p>Создан: {{ $review['createdAt'] }}</p>
                            <p>Изменен: {{ $review['updatedAt'] }}</p>
                            <p>Отзыв на товар: {{ $review['product']['name'] }}</p>
                        </li>
                    @endforeach
                </ol>
            </div>
            <x-pagination :model="$userPaginatedReviews" />
        @endif
    </div>
</x-admin-layout>
