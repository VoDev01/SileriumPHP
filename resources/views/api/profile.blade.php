<x-api-layout>
    <x-slot name="title">
        Профиль | Silerium API
    </x-slot>
    <style>
        #logout {
            color: #6c757d;
        }

        #logout:hover {
            color: black;
        }
    </style>
    @if (Session::has('first_phone_warn'))
        <script type="text/javascript">
            toastr.options.preventDuplicates = true;
            toastr.warning(Session::get('first_phone_warn'));
        </script>
    @endif
    <div class="container">
        <h3>{{ $user->name }}</h3>
        <div class="row">
            <div class="col">
                @php
                    $emailVerified = $user->email_verified_at == null ? 'Потвердить' : '';
                @endphp
                <p>Email: {{ $user->email }} <a class="text-danger text-decoration-none"
                        href="/user/email/verify">{{ $emailVerified }}</a></p>
                @php
                    $phoneVerified = $user->phoneVerified == 0 && $user->phone != null ? 'Подтвердить' : '';
                @endphp
                @if ($user->phone != null)
                    <p>Номер телефона: {{ $user->phone }} <a class="text-danger text-decoration-none"
                            href="/user/phone/verify">{{ $phoneVerified }}</a></p>
                @else
                    <p>Номер телефона: Не указан</p>
                @endif
            </div>
            <div class="col">
                <p>API токен: {{ session('accessToken') ?? 'Не сгенерирован' }}</p>
                @if (session('accessToken') === null)
                    <form action='/api/v1/secret' method="POST">
                        @csrf
                        <button class="btn btn-success" type="submit">Сгенерировать</button>
                    </form>
                @else
                    <form action='/api/v1/secret/refresh' method="POST">
                        @csrf
                        <button class="btn btn-success" type="submit">Регенерировать</button>
                    </form>
                @endif
            </div>
        </div>
        <div class="row">
            <!--<div class="col-3">
                <a class="btn btn-outline-secondary text-decoration-none" href="/user/edit_profile">Редактировать профиль</a>
            </div>-->
            <form class="form-inline col-2" action="/api/v1/logout" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger text-decoration-none" id="logout">Выйти из
                    профиля</button>
            </form>
        </div>
    </div>
</x-api-layout>
