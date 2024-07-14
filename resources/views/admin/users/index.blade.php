<x-admin-layout>
    <x-slot name="title">
        Админ панель - Пользователи | Silerium
    </x-slot>
    <div class="container">
        <h1>Пользователи</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td>Id</td>
                    <td>Имя</td>
                    <td>Фамилия</td>
                    <td>Email</td>
                    <td>Email подтвержден</td>
                    <td>Страна</td>
                    <td>Город</td>
                    <td>Адрес</td>
                    <td>Номер телефона</td>
                    <td>Телефон подтвержден</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{$user->id}}</td>
                        <td>{{$user->name}}</td>
                        <td>{{$user->surname}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->emailVerified}}</td>
                        <td>{{$user->country}}</td>
                        <td>{{$user->city}}</td>
                        <td>{{$user->homeAdress}}</td>
                        <td>{{$user->phone}}</td>
                        <td>{{$user->phoneVerified}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-admin-layout>