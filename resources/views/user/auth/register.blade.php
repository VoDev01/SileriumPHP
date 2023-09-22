<x-layout>
    <x-slot name="title">
        Регистрация
    </x-slot>
    <script src="~/lib/jquery/dist/jquery.min.js"></script>
    <script src="~/lib/jquery-validation/dist/jquery.validate.min.js"></script>
    <script src="~/lib/jquery-validation-unobtrusive/jquery.validate.unobtrusive.min.js"></script>

    <h2 class="text-center">Регистрация</h2>
    <div class="container" style="width: 500px;">
        <form action="/user/postregister" method="POST" enctype="multipart/form-data">
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
                <label class="form-label" for="password">Пароль</label>
                <input class="form-control" type="password" id="password" name="password" />
                <x-error field="password"/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="password_confirmation">Повторите пароль</label>
                <input class="form-control" type="password" id="password_confirmation" name="password_confirmation" />
                <x-error field="password_confirmation"/>
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
            <button class="btn btn-outline-primary mb-3" type="submit">Зарегистрироваться</button>
        </form>
    </div>
</x-layout>