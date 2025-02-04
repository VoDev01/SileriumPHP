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
            $('#sendOrderId').submit();
        });
    </script>
    <form action="/payment/create_payment_request" id="sendOrderId" method="post">
        @csrf
        <input type="hidden" id="orderId" name="orderId" value="{{ $orderId }}">
    </form>
</body>

</html>