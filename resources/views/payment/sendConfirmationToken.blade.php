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
        if ($('#confirmationToken').val() != null) {
            $('#sendConfirmationToken').submit();
        }
    </script>
    <form action="/payment/receive_confirmation_token" id="sendConfirmationToken" method="post">
        @csrf
        <input type="hidden" id="confirmationToken" name="confirmationToken" value="{{ $confirmationToken }}">
        <input type="hidden" id="orderId" name="orderId" value="{{ $orderId }}">
    </form>
</body>

</html>
