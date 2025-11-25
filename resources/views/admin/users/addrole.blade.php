<x-admin-layout>
    <x-slot name="title">
        Админ панель - Управление ролями | Silerium
    </x-slot>
    <div class="container">
        <h1>Управление ролями</h1>
        <form action="/admin/users/roles/add" method="POST">
            @csrf
            <label class="form-label" for="role">Роль</label>
            <input class="form-control" type="text" name="role" id="role" required>
            <button type="submit" class="btn btn-success">
                Создать
            </button>
        </form>
    </div>
</x-admin-layout>
