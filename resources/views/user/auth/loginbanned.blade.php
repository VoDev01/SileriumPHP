<x-banned-layout>
    <x-slot name="title">
        Логин | Silerium
    </x-slot>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script type="module" src="{{asset('js/login.js')}}"></script>
    <script type="module" src="{{asset('js/countdown_request_attempts.js')}}"></script>

    <h2 class="text-center">Вход</h2>
    <div class="container" style="width: 500px;">
        <form id="login_form" action="/user/login" method="POST">
            @csrf
            <input hidden name="api" value="{{ $api }}" />
            <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <input class="form-control" type="email" id="email" name="email" value="{{ old('email') }}" />
                <x-error field="email" id="error-email" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="password">Пароль</label>
                <input class="form-control" type="password" id="password" name="password" />
                <x-error field="password" id="error-password" />
                <x-error field="attempts_available_in" id="error-attempts_available_in" />
            </div>
            <div class="mb-3 row">
                <div class="col-6">
                    <label for="remember_me" class="form-label">Запомнить</label>
                    <input type="checkbox" class="form-check" id="remember_me" value="0" name="remember_me" />
                </div>
                <div class="col-6">
                    <a class="d-block h-100 w-100 text-secondary text-decoration-underline text-end"
                        style="padding-top: 3px; font-size: 15px;" href="/user/forgotpassword">
                        Забыли пароль?
                    </a>
                </div>
            </div>
            <div class="row mb-3 justify-content-center">
                <a class="btn btn-outline-secondary text-decoration-none col-5" href="/user/register">
                    Зарегистрироваться
                </a>
                <div class="col-2"></div>
                <button class="btn btn-outline-primary col-5" id="login_button" type="submit">Войти</button>
            </div>
            <div class="mb-3 align-content-center justify-content-center">
                <p class="text-secondary text-center">Или</p>
            </div>

            <div class="row mb-3">
                <a class="col" href="/user/sign_in_google">
                    <img class="d-block mx-auto" src="/media/images/logo/web_light_rd_na@1x.png">
                </a>

                <a class="col" href="/user/sign_in_yandex">
                    <img class="d-block mx-auto" style="border-radius: 50%;" src="/media/images/logo/image-1.png">
                </a>
            </div>
        </form>
    </div>
</x-banned-layout>
