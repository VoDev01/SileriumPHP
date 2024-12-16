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
            <table class="table table-bordered">
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
                            <td>{{ implode(', ', array_column($user->roles->all(), 'role')) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <x-pagination :model="$users"/>
        </div>
        <p>
            <a class="btn" data-bs-toggle="collapse" href="#roles" aria-expanded="false"
                aria-controls="roles">
                <i class="bi bi-arrow-down" id="rolesArrow"></i> Роли
            </a>
        </p>
        <div class="collapse" id="roles" style="width: 300px;">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <td>Id</td>
                        <td>Роль</td>
                    </tr>
                </thead>
                <tbody id="roles">
                    @foreach ($roles as $role)
                        <tr>
                            <td>{{ $role->id }}</td>
                            <td>{{ $role->role }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <x-pagination :model="$roles" />
        </div>
        <script type="module">
            $(document).ready(function(){
                var foundUsers = document.getElementById('foundUsers');
                foundUsers.addEventListener('show.bs.collapse', function(){
                    $('#foundUsersArrow').addClass('bi-arrow-up');
                    $('#foundUsersArrow').removeClass('bi-arrow-down');
                });
                foundUsers.addEventListener('hide.bs.collapse', function(){
                    $('#foundUsersArrow').addClass('bi-arrow-down');
                    $('#foundUsersArrow').removeClass('bi-arrow-up');
                });
                var roles = document.getElementById('roles');
                roles.addEventListener('show.bs.collapse', function(){
                    $('#rolesArrow').addClass('bi-arrow-up');
                    $('#rolesArrow').removeClass('bi-arrow-down');
                });
                roles.addEventListener('hide.bs.collapse', function(){
                    $('#rolesArrow').addClass('bi-arrow-down');
                    $('#rolesArrow').removeClass('bi-arrow-up');
                });
            });
        </script>
    </div>
</x-admin-layout>
