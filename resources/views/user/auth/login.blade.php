<x-layout>
    <x-slot name="title">
        Логин | Silerium
    </x-slot>
    <script type="module">
        $('#login_button').on('click', function(event) {
            event.preventDefault();
            var existing_errors = document.getElementsByClassName('error');
            for (let index = 0; index < existing_errors.length; index++) {
                existing_errors[index].remove();
            }
            $.ajax({
                type: 'POST',
                url: '/user/postlogin',
                dataType: 'json',
                headers : {
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                }
                data: $("#login_form").serialize(),
                error: function(data) {
                    var all_errors = data.responseJSON.errors;
                    $.each(all_errors, function(key, value) {
                        var error_text = document.createElement('span');
                        error_text.id = key + '-error';
                        error_text.classList.add('error');
                        error_text.classList.add('text-danger');
                        error_text.innerHTML = value[0];
                        var field_id = '#' + key;
                        $(field_id).after(error_text);
                    });
                }
            });
        });
    </script>

    <h2 class="text-center">Вход</h2>
    <div class="container" style="width: 500px;">
        <form id="login_form" action="/user/postlogin" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <input class="form-control" type="email" id="email" name="email" value="{{ old('email') }}" />
                <x-error field="email" id="error-email" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="password">Пароль</label>
                <input class="form-control" type="password" id="password" name="password" />
                <x-error field="password" id="error-password" />
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
                <button class="btn btn-outline-primary col-4" id="login_button" type="submit">Войти</button>
            </div>
        </form>
    </div>
</x-layout>
