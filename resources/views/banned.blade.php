<x-banned-layout>
    <x-slot name="title">
        Доступ ограничен | Silerium
    </x-slot>

    <div class="pb-3 d-flex flex-column align-items-center justify-content-center h-100">
        <h1 class="text-danger">Вы были заблокированы!</h1>
        <p>Причина: {{ $ban->reason }}</p>
        <p>Срок блокировки: {{ $ban->duration . ' ' . $ban->timeType }}</p>
        <p>{{ $ban->bannedAt }}</p>
        <a class="btn btn-success" href="#">Обратиться в службу поддержки</a>
    </div>
</x-banned-layout>