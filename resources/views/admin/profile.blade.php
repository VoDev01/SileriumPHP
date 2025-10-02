<x-admin-layout>
    <x-slot name="title">
        Админ панель - Профиль | Silerium
    </x-slot>
    <style>
        #logout{
            color: #6c757d;
        }
        #logout:hover{
            color: black;
        }
    </style>
    @if(Session::has('first_phone_warn'))
        <script type="text/javascript">
            toastr.options.preventDuplicates = true;
            toastr.warning(Session::get('first_phone_warn'));
        </script>
    @endif
    <div class="container">
        <div class="row mb-3">
            <div class="col-2">
                <img alt="Картинка профиля" src="{{$user->profilePicture}}" 
                width="168" height="168" />
            </div>
            <div class="col-10">
                <h3>{{$user->name}} {{$user->surname}}</h3>
                @php
                    $emailVerified = $user->email_verified_at == null ? "не подтвержден" : "";
                @endphp
                <p>Email: {{$user->email}} <a class="text-danger text-decoration-none" href="/user/email/verify">{{$emailVerified}}</a></p>
                @if ($user->birthDate != null)
                    <p>День рождения: {{$user->birthDate->format('Y-m-d')}}</p>
                @endif
                @php
                    $phoneVerified = ($user->phoneVerified == 0 && $user->phone != null) ? "не подтвержден" : "";
                @endphp
                @if($user->phone != null)
                    <p>Номер телефона: {{$user->phone}} <a class="text-danger text-decoration-none" href="/user/phone/verify">{{$phoneVerified}}</a></p>
                @else
                    <p>Номер телефона: Не указан</p>
                @endif
                @if($user->city != null)
                <p>Город: {{$user->city}}</p>
                @else
                    <p>Город: Не указан</p>
                @endif
                <p>Страна проживания: {{$user->country}}</p>
                @if ($user->homeAdress == null)
                    <p>Адрес доставки: Не указан</p>
                @else
                    <p>Адрес доставки: {{$user->homeAdress}}</p>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <a class="btn btn-outline-secondary text-decoration-none" href="/admin/edit_profile">Редактировать профиль</a>
            </div>
            <form class="form-inline col-2" action="/admin/logout" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger text-decoration-none" id="logout">Выйти из профиля</button>
            </form>
        </div>
    </div>
</x-admin-layout>