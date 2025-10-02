<x-admin-layout>
    <x-slot name="title">
        Контроль API dropbox
    </x-slot>
    <div class="container">
        <h1>Dropbox api</h1>
        @if ($refreshToken !== "")
            <h4 class="text-success">Приложение авторизовано</h4>
            <form action="/admin/revoke_dropbox_token" method="POST">
                <button class="btn">Отозвать токен</button>
            </form>
        @else
            <h4 class="text-danger">Приложение неавторизовано</h4>
            <a href="{{ $dropboxAuthUrl }}" class="btn">Авторизовать приложение</a>
        @endif
    </div>
</x-admin-layout>
