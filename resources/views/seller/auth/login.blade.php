<x-seller-layout>
    <x-slot:title>
        Логин | Silerium Partner
    </x-slot>
    <script type="module">
        $("#login_button").on("click", function(e){
            e.preventDefault();
            $.ajax({
                method: "POST",
                url: "/seller/login",
                accepts: "json",
                headers: { 
                    "X-CSRF-TOKEN" : $('meta[name="csrf-token"]').attr('content')
                },
                data: $("#login_form").serialize(),
                success: function(data){
                    window.location.href = data.redirect;
                },
                error: function(data){
                    let all_errors = data.responseJSON.errors;
                    $.each(all_errors, function(key, value) {
                        let error_text = document.createElement('span');
                        error_text.id = key + '-error';
                        error_text.classList.add('error');
                        error_text.classList.add('text-danger');
                        error_text.innerHTML = value[0];
                        let field_id = '#' + key;
                        $(field_id).after(error_text);
                    });
                }
            });
        });
    </script>
    <div class="container" style="width: 500px;">
        <h1>Вход в личный кабинет</h1>
        <form id="login_form" method="POST" enctype="multipart/form-data" action="/seller/login">
            <div class="mb-3">
                <label for="email" class="form-label">Email пользователя</label>
                <input type="email" class="form-control" name="email" id="email" />
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Пароль</label>
                <input type="password" class="form-control" name="password" id="password" />
            </div>
            <div class="mb-3 row">
                <div class="col-6">
                    <label for="remember_me" class="form-label">Запомнить</label>
                    <input type="checkbox" class="form-check" id="remember_me" value="0" name="remember_me" />
                </div>
                <div class="col-6">
                    <a class="d-block h-100 w-100 text-secondary text-decoration-underline text-end"
                        style="padding-top: 3px; font-size: 15px;" href="/seller/forgotpassword">
                        Забыли пароль?
                    </a>
                </div>
            </div>
            <div class="row mb-3 justify-content-center">
                <a class="btn btn-outline-secondary text-decoration-none col-5" href="/seller/register">
                    Зарегистрироваться
                </a>
                <div class="col-2"></div>
                <button class="btn btn-outline-primary col-5" id="login_button" type="submit">Войти</button>
            </div>
        </form>
    </div>
</x-seller-layout>
