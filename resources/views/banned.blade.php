<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/site.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title }}</title>
</head>

<body>
    @yield('scripts')
    <div class="container-fluid g-0" style="height: 800px;">
        <main class="pb-3 align-content-center">
            <h1 class="text-danger">Вы были заблокированы!</h1>
            <p>По причине: {{$ban->reason}}</p>
            <p>Срок блокировки: {{$ban->time . " " . $timeType}}</p>
            <a class="btn btn-success" href="#">Обратиться в слжбу поддержки</a>
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