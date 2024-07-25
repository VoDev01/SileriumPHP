<x-seller-layout>
    <x-slot:title>
        Silerium Partner | Регистрация
    </x-slot>
    <div class="container" style="width:500px;">
        <h1>Заполните данные</h1>

        <form method="POST" action="/seller/post_register">
            <div class="mb-3">
                <label for="type" class="form-label">Тип организации</label>
                <select class="form-select form-select-lg" name="type" id="type">
                    <option value="1">ООО</option>
                    <option value="2">ИП</option>
                    <option value="3">АО</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Название организации</label>
                <input type="text" class="form-control" name="name" id="name" />
            </div>
            <div class="mb-3">
                <label for="tax_system" class="form-label">Система налогообложения</label>
                <select class="form-select form-select-lg" name="tax_system" id="tax_system">
                    <option value="1">УСН</option>
                    <option value="2">ОСНО</option>
                    <option value="3">ЕСХН</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="nickname" class="form-label">Название магазина</label>
                <input type="text" class="form-control" name="nickname" id="nickname"
                    aria-describedby="nickname_desc" />
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email организации</label>
                <input type="email" class="form-control" name="email" id="email" />
            </div>
            <div class="mb-3">
                <label for="img" class="form-label">Логотип организации</label>
                <input type="file" class="form-control" name="img" id="img" />
            </div>
            <button type="submit" class="btn btn-primary">
                Зарегистрировать
            </button>
        </form>
    </div>
</x-seller-layout>
