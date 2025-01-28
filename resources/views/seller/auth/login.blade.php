<x-seller-layout>
    <x-slot:title>
        Логин | Silerium Partner
    </x-slot>
    <script type="module">
        $("#login_button").on("click", function(e){
            e.preventDefault();
            $.ajax({
                method: "POST",
                url: "/seller/login",
                accepts: "json",
                headers: { 
                    "X-CSRF-TOKEN" : $('meta[name="csrf-token"]').attr('content')
                },
                data: $("#login_form").serialize(),
                success: function(data){
                    window.location.href = data.redirect;
                },
                error: function(data){
                    let all_errors = data.responseJSON.errors;
                    $.each(all_errors, function(key, value) {
                        let error_text = document.createElement('span');
                        error_text.id = key + '-error';
                        error_text.classList.add('error');
                        error_text.classList.add('text-danger');
                        error_text.innerHTML = value[0];
                        let field_id = '#' + key;
                        $(field_id).after(error_text);
                    });
                }
            });
        });
    </script>
    <div class="container" style="width: 500px;">
        <h1>Вход в личный кабинет продавца</h1>
        <form id="login_form" method="POST" enctype="multipart/form-data" action="/seller/login">
            <div class="mb-3">
                <label for="email" class="form-label">Email пользователя</label>
                <input type="email" class="form-control" name="email" id="email" />
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Пароль</label>
                <input type="password" class="form-control" name="password" id="password" />
            </div>
            <button type="submit" class="btn btn-primary" id="login_button">
                Войти
            </button>
        </form>
    </div>
</x-seller-layout>
