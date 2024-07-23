<x-admin-layout>
    <x-slot name="title">
        Админ панель - Управление ролями | Silerium
    </x-slot>
    <div class="container">
        <h1>Управление ролями</h1>
        <p>
            <a class="btn btn-primary" data-bs-toggle="collapse" href="#users" aria-expanded="false" aria-controls="users">
                Пользователи
            </a>
        </p>
        <div class="collapse" id="users">
            <form class="mb-3" style="width: 250px;">
                <h4>Найти пользователя</h4>
                <input hidden name="load_with" id="load_with" value="roles" />
                <div class="mb-3">
                    <label for="id" class="form-label">Id</label>
                    <input type="number" class="form-control" name="id" id="id" defa />
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="email" />
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Телефон</label>
                    <input type="phone" class="form-control" name="phone" id="phone" />
                </div>
                <button type="submit" class="btn btn-primary" id="find_user">
                    Найти
                </button>
            </form>
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
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ implode(', ', array_column($user->roles->all(), 'role')) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <x-pagination :model="$users" />
        </div>
        <p>
            <a class="btn btn-primary" data-bs-toggle="collapse" href="#roles" aria-expanded="false"
                aria-controls="roles">
                Роли
            </a>
        </p>
        <div class="collapse" id="roles">
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
        </div>
    </div>
    <script type="module">
        $(document).ready(function() {
            $("#find_user").on("click", function(e) {
                console.log("clicked");
                e.preventDefault();
                let id = $("#id").val();
                let email = $("#email").val();
                let phone = $("#phone").val();

                let url = "/admin/users/find/" + email;

                if (id.length != 0) {
                    url += "/" + id;
                }
                if (phone.length != 0) {
                    url += "/" + phone;
                }

                if (email) {
                    console.log("not null");
                    $.ajax({
                        method: "GET",
                        dataType: "json",
                        url: url,
                        success: function(data) {
                            console.log("success");
                            $("#user").empty();
                            $("#user").append("<td>" + data.users.id + "</td>");
                            $("#user").append("<td>" + data.users.email + "</td>");
                            $("#user").append("<td>" + data.users.roles.join(", ") +
                                "</td>");
                        },
                        error: function(data, status, error) {
                            console.log(data.responseText);
                        }
                    });
                } else {
                    console.log("null");
                    $("#user").empty();
                }
            });
        });
    </script>
</x-admin-layout>
