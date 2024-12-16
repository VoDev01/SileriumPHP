<x-admin-layout>
    <x-slot name="title">
        Админ панель - Отзывы пользователя | Silerium
    </x-slot>
    <div class="container">
        <h1>Отзывы пользователей</h1>
        <x-search-form header="Поиск пользователей" submit_id="find_user" :$inputs :$queryInputs />

        @if ($users != null)
            <p>
                <button class="btn" type="button" data-bs-toggle="collapse" data-bs-target="#foundUsers"
                    aria-expanded="false" aria-controls="foundUsers">
                    <i class="bi bi-arrow-down" id="foundUsersArrow"></i> Пользователи
                </button>
            </p>
            <div class="collapse" id="foundUsers">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td>Id</td>
                            <td>Имя</td>
                            <td>Фамилия</td>
                            <td>Email</td>
                            <td>Телефон</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            @php
                                $user = (object) $user;
                            @endphp
                            <tr>
                                <td>{{ $user->ulid }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->surname }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <x-pagination :model="$users"/>
            </div>
            <form action="/admin/users/reviews" method="POST" style="width: 300px;">
                <h5>Показать отзывы пользователя</h5>
                @csrf
                <input name="searchKey" value="{{ session('searchKey') }}" hidden />
                <div class="mb-3">
                    <label for="id" class="form-label">Id пользователя</label>
                    <input type="text" class="form-control" name="id" id="id" />
                </div>
                <button type="submit" class="btn btn-primary">
                    Отправить
                </button>
            </form>
            @if ($reviews != null && $message == null)
                <h5>Отзывы пользователя {{ $user->name }} {{ $user->surname }}</h5>
                <ol>
                    @foreach ($reviews as $review)
                        @php
                            $review = (object)$review;
                            $review->product = (object)$review->product;
                        @endphp
                        <li class="border-bottom border-dark">
                            <p>Название отзыва: {{ $review->title }}</p>
                            <p>Плюсы: {{ $review->pros }}</p>
                            <p>Минусы: {{ $review->cons }}</p>
                            <p>Комментарий: {{ $review->comment }}</p>
                            <p>Оценка: {{ $review->rating }}</p>
                            <p>Создан: {{ $review->created_at }}</p>
                            <p>Изменен: {{ $review->updated_at }}</p>
                            <p>Отзыв на товар: {{ $review->product->name }}</p>
                        </li>
                    @endforeach
                </ol>
                <x-pagination :model="$reviews"/>
            @else
                <span class="mt-3">{{$message}}</span>
            @endif
        @endif
    </div>
    <script type="module">
        $(document).ready(function() {
                    var foundUsers = document.getElementById('foundUsers');
                    foundUsers.addEventListener('show.bs.collapse', function() {
                        $('#foundUsersArrow').removeClass("bi-arrow-down");
                        $('#foundUsersArrow').addClass("bi-arrow-up");
                    });
                    foundUsers.addEventListener('hide.bs.collapse', function() {
                        $('#foundUsersArrow').removeClass("bi-arrow-up");
                        $('#foundUsersArrow').addClass("bi-arrow-down");
                    });
                }
    </script>
</x-admin-layout>
