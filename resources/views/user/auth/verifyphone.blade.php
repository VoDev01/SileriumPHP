<x-layout>
    <x-slot name="title">
        Подтверждение телефона | Silerium
    </x-slot>
    <script type="module">
        let linkSent = window.sessionStorage.getItem('link_sent');
        let timer = 60,
        seconds;
        if (linkSent) {
            $('#send_code_button').prop('disabled', true);
            $('#send_code_disabled').prop('hidden', false);
            setInterval(() => {
                seconds = parseInt(timer % 60, 10);
                seconds = seconds < 10 ? "0" + seconds : seconds;
                
                $('#send_code_disabled').text("Отправить код повторно можно будет через " + seconds + " секунд.");

                if(--timer < 0)
                {
                    timer = 60;
                    $('#send_code_button').prop('disabled', false);
                    $('#send_code_disabled').prop('hidden', true);
                }
            }, 1000);
        }
    </script>
    <div class="container m-auto" style="width: 500px;">
        <p>На ваш номер было выслано смс с кодом. Введите код в поле ниже.</p>
        <form action="/user/phone/validat_everification" class="mb-2">
            <div class="mb-3" style="width: 100px;">
                <label for="code" class="form-label">Код</label>
                <input type="number" class="form-control" name="code" id="code" min="1111" max="9999" />
            </div>
            <x-error field="verification_code"></x-error>
            <button type="submit" class="btn btn-primary">
                Отправить
            </button>
        </form>
        <p id="send_code_disabled" hidden>Отправить код повторно можно будет через 60 секунд.</p>
        <form action="/user/phone/resend_verification">
            <button id="send_code_button" type="submit" class="btn btn-secondary">
                Прислать код повторно
            </button>
        </form>
    </div>
</x-layout>
