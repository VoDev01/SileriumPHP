<x-layout>
    <x-slot name="title">
        Регистрация | Silerium
    </x-slot>
    <script type="module">
        $('#register_form').on('submit', function(event) {
            event.preventDefault();

            $.ajax({
                type: 'POST',
                url: '/user/postregister',
                dataType: 'json',
                data: $(this).serialize(),
                success: function()
                {
                    window.location.href = '/user/login';
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

    <h2 class="text-center">Регистрация</h2>
    <div class="container" style="width: 500px;">
        <form action="/user/postregister" id="register_form" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="name">Имя</label>
                <input class="form-control" type="text" id="name" name="name" />
                <x-errorajax id="error-name" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="surname">Фамилия</label>
                <input class="form-control" type="text" id="surname" name="surname" />
                <x-errorajax id="error-surname" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <input class="form-control" type="email" id="email" name="email" />
                <x-errorajax id="error-email" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="password">Пароль</label>
                <input class="form-control" type="password" id="password" name="password" />
                <x-errorajax id="error-password" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="password_confirmation">Повторите пароль</label>
                <input class="form-control" type="password" id="password_confirmation" name="password_confirmation" />
                <x-errorajax id="error-password_confirmation" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="birthDate">День рождения</label>
                <input class="form-control" type="date" id="birthDate" name="birthDate" />
                <x-errorajax id="error-birthDate" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="country">Страна проживания</label>
                <input class="form-control" type="text" id="country" name="country" />
                <x-errorajax id="error-country" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="city">Город</label>
                <input class="form-control" type="text" id="city" name="city" />
                <x-errorajax id="error-city" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="homeAdress">Адрес для доствки</label>
                <input class="form-control" type="text" id="homeAdress" name="homeAdress" />
                <x-errorajax id="error-homeAdress" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="phone">Номер телефона</label>
                <input class="form-control" type="text" id="phone" name="phone" />
                <x-errorajax id="error-phone" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="pfp">Картинка профиля</label>
                <input class="form-control" type="file" id="pfp" name="pfp" />
                <x-errorajax id="error-pfp" />
            </div>
            <button class="btn btn-outline-primary mb-3" type="submit">Зарегистрироваться</button>
        </form>
    </div>
</x-layout>
