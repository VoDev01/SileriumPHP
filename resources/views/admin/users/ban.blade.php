<x-admin-layout>
    <x-slot name="title">
        Бан пользователя | Silerium
    </x-slot>
    <div class="container" style="width: 800px;">
        <x-search-form header="Поиск пользователей" :$inputs :$queryInputs />
        @if (isset($users))
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
                            <td>Номер телефона</td>
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
                <x-pagination :model="$users" />
            </div>
            <p>
                <button class="btn" type="button" data-bs-toggle="collapse" data-bs-target="#banUser"
                    aria-expanded="false" aria-controls="banUser">
                    <i class="bi bi-arrow-down" id="banUserArrow"></i> Забанить
                </button>
            </p>
            <div class="collapse" id="banUser">
                <form action="/admin/users/ban" method="POST">
                    <div class="mb-3">
                        <label for="id" class="form-label">Id пользователя</label>
                        <input type="text" class="form-control" name="id" id="id" />
                    </div>
                    <div class="mb-3">
                        <label for="reason" class="form-label">Причина бана</label>
                        <textarea class="form-control" name="reason" id="reason" rows="5" cols="50" autocomplete="off"
                            style="resize: none;"></textarea>
                    </div>
                    <div class="mb-3 row">
                        <div class="col">
                            <label for="banTime" class="form-label">Время бана</label>
                            <input type="text" class="form-control" name="banTime" id="banTime" />
                        </div>
                        <div class="col">
                            <label for="timeType" class="form-label">В</label>
                            <select name="timeType" id="timeType" class="form-select" style="width: 125px;" required>
                                <option value="seconds">Секундах</option>
                                <option value="minutes" selected>Минутах</option>
                                <option value="hours">Часах</option>
                                <option value="days">Днях</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-danger">Забанить</button>
                    </div>
                </form>
            </div>
        @else
            @if (isset($message))
                <span>{{ $message }}</span>
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
            var banUser = document.getElementById('banUser');
            banUser.addEventListener('show.bs.collapse', function() {
                $('#banUserArrow').removeClass("bi-arrow-down");
                $('#banUserArrow').addClass("bi-arrow-up");
            });
            banUser.addEventListener('hide.bs.collapse', function() {
                $('#banUserArrow').removeClass("bi-arrow-up");
                $('#banUserArrow').addClass("bi-arrow-down");
            });
        });
    </script>
</x-admin-layout>
