<x-api-layout>
    <x-slot name="title">
        Регистрация | Silerium API
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
        <form action="/api/v1/register" id="register_form" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="name">Имя</label>
                <input class="form-control" type="text" id="name" name="name" value="{{old('name')}}"/>
                <x-error field="name" id="error-name" />
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
                <label class="form-label" for="phone">Номер телефона</label>
                <input class="form-control" type="text" id="phone" name="phone" value="{{old('phone')}}"/>
                <x-error field="phone" id="error-phone" />
            </div>
            <button class="btn btn-outline-primary mb-3" type="submit">Зарегистрироваться</button>
        </form>
    </div>
</x-api-layout>
