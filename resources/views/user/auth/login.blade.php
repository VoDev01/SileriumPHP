<x-layout>
    <x-slot name="title">
        Логин | Silerium
    </x-slot>
    <script type="module">
        $('#login_form').on('submit', function(event) {
            event.preventDefault();

            $.ajax({
                type: 'POST',
                url: '/user/postlogin',
                dataType: 'json',
                data: $(this).serialize(),
                success: function()
                {
                    window.location.href = '/user/profile';
                    console.log('User validated');
                },
                error: function(data) {
                    var response = data.responseJSON;
                    var all_errors = response.errors;

                    $.each(all_errors, function(key, value) {
                        $('#error-'+key).text("");
                        $('#error-'+key).text(value);
                    });
                }
            });
        });
    </script>

    <h2 class="text-center">Вход</h2>
    <div class="container" style="width: 500px;">
        <form action="/user/postlogin" id="login_form" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <input class="form-control" type="email" id="email" name="email" />
                <x-errorajax id="error-email" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="password">Пароль</label>
                <input class="form-control" type="password" id="password" name="password" />
                <x-errorajax id="error-password"/>
            </div>
            <div class="mb-3 row">
                <div class="col-6">
                    <label for="remember_me" class="form-label">Запомнить</label>
                    <input type="checkbox" class="form-check" id="remember_me" name="remember_me" />
                </div>
                <div class="col-6">
                    <a class="d-block h-100 w-100 text-secondary text-decoration-underline text-end"
                        style="padding-top: 3px; font-size: 15px;" href="/user/forgotpassword">
                        Забыли пароль?
                    </a>
                </div>
            </div>
            <div class="row mb-3 justify-content-center">
                <a class="btn btn-outline-secondary text-decoration-none col-4" href="/user/register">
                    Зарегистрироваться
                </a>
                <div class="col-2"></div>
                <button class="btn btn-outline-primary col-4" type="submit">Войти</button>
            </div>
        </form>
    </div>
</x-layout>
