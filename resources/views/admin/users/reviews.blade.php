<x-admin-layout>
    <x-slot name="title">
        Админ панель - Отзывы пользователя | Silerium
    </x-slot>
    <div class="container">
        <h4>Поиск отзывов</h4>
        <form class="container" method="POST" action="/admin/users/post_user_search">
            @csrf
            <input hidden name="load_with" id="load_with" value="reviews" />
            <input hidden name="redirect" id="redirect" value="user_reviews" />
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
        </form>
        @if ($userInfoReceived)
            <div class="container" id="user_reviews">
                <h5>{{ $user['name'] }} {{ $user['surname'] }}</h5>
                <img src="{{ $user['profilePicture'] }}" alt="Аватарка пользователя" />
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
            </div>
        @endif
    </div>
</x-admin-layout>
