<x-layout>
    <x-slot name="title">
        Восстановление пароля | Silerium
    </x-slot>
    <div class="container mx-auto">
        <h1>Введите email адрес</h1>
        <form action="/user/forgotpassword" method="post" class="row flex-column">
            <div class="mb-3 col" style="width: 250px;">
                <label for="email" class="form-label">Email</label>
                <input type="text" class="form-control" name="email" id="email">
            </div>
            <div class="row col px-4" style="width: 300px;">
                <a class="btn btn-outline-danger col-5 d-block" href="/user/login" role="button">Отменить</a>
                <div class="col-2"></div>
                <button type="submit" class="btn btn-outline-primary col-5">Отправить</button>
            </div>
        </form>
    </div>
</x-layout>
