<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Создание платежного запроса | Silerium</title>
</head>

<body>

    @php
    @endphp
    <script type="module">
        $(function() {
            $('#createPayment').submit();
        });
    </script>
    <form action="/payment/create_payment_request" id="createPayment" method="post">
        @csrf
    </form>
</body>

</html>