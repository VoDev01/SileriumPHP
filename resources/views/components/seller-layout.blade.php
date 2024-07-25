<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/site.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://www.cbr-xml-daily.ru/money.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title }}</title>
</head>

<body>
    @yield('scripts')
    <header>
        <nav
            class="navbar navbar-expand-sm navbar-toggleable-sm navbar-light border-bottom box-shadow align-content-end">
            <div class="container-fluid">
                <a class="navbar-brand" style="font-size: 28px;" href="/seller/">Silerium Partner</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target=".navbar-collapse" aria-controls="navbarMain" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="navbar-collapse collapse d-sm-inline-flex justify-content-end">
                    <div class="navbar-nav">
                        @if(CheckUserRole::check('seller'))
                            <a class="nav-item nav-link text-black text-decoration-none" href="/seller/orders/all">
                                Заказы
                                <i class="bi bi-truck"></i>
                            </a>
                            <a class="nav-item nav-link text-black text-decoration-none" href="/seller/products">
                                Товары
                                <i class="bi bi-box-seam"></i>
                            </a>
                            <a class="nav-item nav-link text-black text-decoration-none" href="/seller/reviews">
                                Отзывы
                                <i class="bi bi-chat-dots"></i>
                            </a>
                        @endif
                        <a class="nav-item nav-link text-black text-decoration-none" href="/seller/profile">
                            Войти в личный кабинет
                            <i class="bi bi-box-arrow-in-right"></i>
                        </a>
                        @if(!Auth::user())
                            <a class="nav-item nav-link text-black text-decoration-none" href="/seller/register">
                                Зарегестрироваться
                                <i class="bi bi-cash"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <div class="container-fluid g-0">
        <main role="main" class="pb-3">
            {{ $slot }}
        </main>
    </div>

    <footer class="border-top footer">
        <div class="container">
            &copy; 2023 - Silerium
            <br />
            <span class="text-secondary">
                Изображения -
                <a class="text-decoration-none text-secondary" href="https://unsplash.com/">Unsplash, </a>
                <a class="text-decoration-none text-secondary" href="https://www.freepik.com/">Freepik, </a>
                <a class="text-decoration-none text-secondary"
                    href="https://cdn.thewirecutter.com/wp-content/media/2022/10/27inchmonitors-2048px-9791.jpg">Dave
                    Gershgorn</a>
            </span>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>
