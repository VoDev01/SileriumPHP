<x-layout>
    <x-slot name="title">
        Подтверждение email | Silerium
    </x-slot>
    <div class="container m-auto">
        <h1>Подтведите свой email</h1>
        <p class="text-secondary">На ваш email было отправлено письмо с ссылкой на страницу подтвеждения. Перейдите по
            ссылке.</p>
        <form action="/user/email/resend_verification" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">
                Отправить повторно
            </button>
        </form>
    </div>
</x-layout>
