<x-admin-layout>
    <x-slot name="title">
        Админ панель - Пользователи | Silerium
    </x-slot>
    <div class="container">
        <h1>Пользователи</h1>
        <x-search-form header="Поиск пользователей" submit_id="search_users" :$queryInputs :$inputs />
        @if ($users != null)
            <p>
                <button class="btn" type="button" data-bs-toggle="collapse" data-bs-target="#foundUsers" aria-expanded="false"
                    aria-controls="foundUsers">
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
                            <td>Email подтвержден</td>
                            <td>Страна</td>
                            <td>Город</td>
                            <td>Адрес</td>
                            <td>Номер телефона</td>
                            <td>Телефон подтвержден</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        @php
                           $user = (object)$user;
                        @endphp
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->surname }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->emailVerified ?? 'Нет' }}</td>
                                <td>{{ $user->country }}</td>
                                <td>{{ $user->city }}</td>
                                <td>{{ $user->homeAdress }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ $user->phoneVerified != 0 ? 'Да' : 'Нет' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <x-pagination :model="$users" :params="['searchKey' => session('searchKey')]" />
            </div>
        @else
            <span>{{$message}}</span>
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
        });
    </script>
</x-admin-layout>
