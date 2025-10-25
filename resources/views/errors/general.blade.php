<x-layout>
    <x-slot name="title">
        Ошибка {{ $status }} | Silerium
    </x-slot>
    <div class="d-flex flex-column align-items-center justify-content-center h-100">
            <h1 class="text-center text-red">{{ $status }}</h1>
            <p class="text-center">{{ $message }}</p>
    </div>
</x-layout>
