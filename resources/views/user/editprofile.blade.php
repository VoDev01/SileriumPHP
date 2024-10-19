<x-layout>

    <x-slot name="title">
        Профиль | Silerium
    </x-slot>
    <script type="module">
        $('#edit_profile_form').on('submit', function(event) {
            event.preventDefault();

            $.ajax({
                type: 'POST',
                url: '/user/editprofile',
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
    <div class="m-auto" style="width: 500px;">
        <form class="row flex-column" id="edit_proifle_form" action="/user/edit_profile" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="name">Имя</label>
                <input class="form-control" type="text" id="name" name="name" />
                <x-error field="name"/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="surname">Фамилия</label>
                <input class="form-control" type="text" id="surname" name="surname" />
                <x-error field="surname"/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <input class="form-control" type="email" id="email" name="email" />
                <x-error field="email"/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="birthDate">День рождения</label>
                <input class="form-control" type="date" id="birthDate" name="birthDate" />
                <x-error field="birthDate"/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="country">Страна проживания</label>
                <input class="form-control" type="text" id="country" name="country" />
                <x-error field="country"/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="city">Город</label>
                <input class="form-control" type="text" id="city" name="city" />
                <x-error field="city"/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="homeAdress">Адрес для доствки</label>
                <input class="form-control" type="text" id="homeAdress" name="homeAdress" />
                <x-error field="homeAdress"/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="phone">Номер телефона</label>
                <input class="form-control" type="text" id="phone" name="phone" />
                <x-error field="phone"/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="pfp">Картинка профиля</label>
                <input class="form-control" type="file" id="pfp" name="pfp" />
                <x-error field="pfp"/>
            </div>
            <div class="row justify-content-center mb-3">
                <a class="btn btn-outline-danger mb-3 col-3" href="/user/profile">Отменить</a>
                <div class="col-1"></div>
                <button class="btn btn-outline-primary mb-3 col-3" type="submit">Редактировать</button>
            </div>
            </div>
        </form>
    </div>
</x-layout>
