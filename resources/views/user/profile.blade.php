<x-layout>
    <x-slot name="title">
        Профиль
    </x-slot>
    <style>
        #logout{
            color: #6c757d;
        }
        #logout:hover{
            color: black;
        }
    </style>
    <div class="container">
        <div class="row mb-3">
            <div class="col-2">
                <img alt="Картинка профиля" src="{{asset($user->profilePicture)}}" width="168" height="168" />
            </div>
            <div class="col-10">
                <h3>{{$user->name}} {{$user->surname}}</h3>
                @if ($user->birthDate != null)
                    <p>День рождения: {{$user->birthDate}}</p>
                @endif
                @if($user->phone != null)
                    <p>Номер телефона: {{$user->phone}}</p>
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
        <form class="form-inline" action="/user/logout" method="POST">
            @csrf
            <a class="btn btn-outline-secondary text-decoration-none" href="/user/editprofile">Редактировать профиль</a>
            <button type="submit" class="btn btn-outline-danger text-decoration-none" id="logout">Выйти из профиля</button>
        </form>
    </div>
</x-layout>