<x-seller-layout>
    <x-slot:title>
        Личный кабинет продавца | Silerium Partner
    </x-slot>
    <div class="container" style="width: 450px;">
        <h1>Изменить данные продавца</h1>
        <form method="POST" enctype="multipart/form-data" action="/seller/account/edit">
            <div class="mb-3">
                <label for="organization_type" class="form-label">Тип организации</label>
                <select class="form-select form-select-lg" name="organization_type" id="organization_type">
                    <option value="1">ООО</option>
                    <option value="2">ИП</option>
                    <option value="3">АО</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="organization_name" class="form-label">Название организации</label>
                <input type="text" class="form-control" name="organization_name" id="organization_name" />
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
                <label for="organization_email" class="form-label">Email организации</label>
                <input type="email" class="form-control" name="organization_email" id="organization_email" />
            </div>
            <div class="mb-3">
                <label for="logo" class="form-label">Логотип организации</label>
                <input type="file" class="form-control" name="logo" id="logo" />
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email продавца</label>
                <input type="email" class="form-control" name="email" id="email" />
            </div>
            <button type="submit" class="btn btn-primary">
                Изменить
            </button>
        </form>
    </div>
</x-seller-layout>
