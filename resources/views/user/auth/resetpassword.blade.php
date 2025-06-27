<x-layout>
    <x-slot name="title">
        Восстановление пароля - новый пароль | Silerium
    </x-slot>
    <div class="container mx-auto">
        <h1>Введите новый пароль</h1>
        <form action="/user/reset_password" method="post" class="row flex-column">
            <div class="mb-3 col">
                <label for="password" class="form-label">Пароль</label>
                <input type="text" class="form-control" name="password" id="password">
            </div>
            <div class="mb-3 col">
              <label for="password_confirmation" class="form-label">Подтвердите пароль</label>
              <input type="text"
                class="form-control" name="password_confirmation" id="password_confirmation">
            </div>
            <div class="row col px-4" style="width: 300px;">
                <a class="btn btn-outline-danger col-5 d-block" href="/user/login" role="button">Отменить</a>
                <div class="col-2"></div>
                <button type="submit" class="btn btn-outline-primary col-5">Отправить</button>
            </div>
        </form>
    </div>
</x-layout>
