<x-layout>
    <x-slot name="title">
        Регистрация | Silerium
    </x-slot>
    <!--<script type="module">
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
                    let response = data.responseJSON;
                    let all_errors = response.errors;

                    $.each(all_errors, function(key, value) {
                        $('#error-'+key).text(value);
                    });
                }
            });
        });
    </script>-->

    <h2 class="text-center">Регистрация</h2>
    <div class="container" style="width: 500px;">
        <form action="/user/register" id="register_form" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="name">Имя</label>
                <input class="form-control" type="text" id="name" name="name" value="{{old('name')}}"/>
                <x-error field="name" id="error-name" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="surname">Фамилия</label>
                <input class="form-control" type="text" id="surname" name="surname" value="{{old('surname')}}"/>
                <x-error field="surname" id="error-surname" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <input class="form-control" type="email" id="email" name="email" value="{{old('email')}}"/>
                <x-error field="email" id="error-email" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="password">Пароль</label>
                <input class="form-control" type="password" id="password" name="password" />
                <x-error field="password" id="error-password" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="password_confirmation">Повторите пароль</label>
                <input class="form-control" type="password" id="password_confirmation" name="password_confirmation" />
                <x-error field="password_confirmation" id="error-password_confirmation" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="birthDate">День рождения</label>
                <input class="form-control" type="date" id="birthDate" name="birthDate" value="{{old('birthDate')}}"/>
                <x-error field="birthDate" id="error-birthDate" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="country">Страна проживания</label>
                <input class="form-control" type="text" id="country" name="country" value="{{old('country')}}"/>
                <x-error field="country" id="error-country" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="city">Город</label>
                <input class="form-control" type="text" id="city" name="city" value="{{old('city')}}"/>
                <x-error field="city" id="error-city" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="homeAdress">Адрес для доствки</label>
                <input class="form-control" type="text" id="homeAdress" name="homeAdress" value="{{old('homeAdress')}}"/>
                <x-error field="homeAdress" id="error-homeAdress" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="phone">Номер телефона</label>
                <input class="form-control" type="text" id="phone" name="phone" value="{{old('phone')}}"/>
                <x-error field="phone" id="error-phone" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="pfp">Картинка профиля</label>
                <input class="form-control" type="file" id="pfp" name="pfp" />
                <x-error field="pfp" id="error-pfp" />
            </div>
            <button class="btn btn-outline-primary mb-3" type="submit">Зарегистрироваться</button>
        </form>
    </div>
</x-layout>
