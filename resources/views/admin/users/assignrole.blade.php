<x-admin-layout>
    <x-slot name="title">
        Админ панель - Управление ролями | Silerium
    </x-slot>
    <div class="container">
        <h1>Управление ролями</h1>
        <x-search-form header="Поиск пользователей" submit_id="search-users" :$inputs :$queryInputs />
        <p>
            <a class="btn" data-bs-toggle="collapse" href="#foundUsers" aria-expanded="false" aria-controls="foundUsers">
                <i class="bi bi-arrow-down" id="foundUsersArrow"></i> Пользователи
            </a>
        </p>
        <div class="collapse" id="foundUsers">
            <table class="table table-bordered mx-5">
                <thead>
                    <tr>
                        <td>Id</td>
                        <td>Email</td>
                        <td>Роли</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr id="user">
                            <td>{{ $user->ulid }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <form action="/admin/user/roles/assign" method="POST">
                                    @csrf
                                    <input type="hidden" name="user" value="{{ $user->email }}">
                                    @foreach ($roles as $role)
                                        @if ($role->role === 'user')
                                            @continue
                                        @endif
                                        @if ($user->roles->where('role', $role->role)->isNotEmpty())
                                            <label for="role[]">{{ $role->role }}</label>
                                            <input type="checkbox" name="role[]" class="roles"
                                                data-value="{{ $role->role }}" checked value="">
                                        @else
                                            <label for="role[]">{{ $role->role }}</label>
                                            <input type="checkbox" name="role[]" class="roles"
                                                data-value="{{ $role->role }}" value="">
                                        @endif
                                    @endforeach
                                    <button class="btn btn-success" type="submit"><i class="bi bi-check"
                                            style="color: #26d802"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <x-pagination :model="$users" />
        </div>
        <script type="module">
            $(document).ready(function() {
                let foundUsers = document.getElementById('foundUsers');
                foundUsers.addEventListener('show.bs.collapse', function() {
                    $('#foundUsersArrow').addClass('bi-arrow-up');
                    $('#foundUsersArrow').removeClass('bi-arrow-down');
                });
                foundUsers.addEventListener('hide.bs.collapse', function() {
                    $('#foundUsersArrow').addClass('bi-arrow-down');
                    $('#foundUsersArrow').removeClass('bi-arrow-up');
                });

                let roles = document.getElementsByClassName("roles");
                roles.forEach(element => {
                    if ($(element).prop("checked") == true) {
                        $(element).val(element.getAttribute("data-value"));
                    } else {
                        $(element).val("");
                    }
                });
            });
        </script>
    </div>
</x-admin-layout>
