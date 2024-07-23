<x-admin-layout>
    <x-slot name="title">
        Админ панель - Заказы пользователя | Silerium
    </x-slot>
    <div class="container">
        <div class="d-flex">
            <x-api-search-form action_url="/admin/users/post_user_search" load_with="orders" redirect="user_orders"
                header="Поиск пользователей">
                <div class="mb-3">
                    <label for="name" class="form-label">Имя</label>
                    <input type="text" class="form-control" name="name" id="name" />
                </div>
                <div class="mb-3">
                    <label for="surname" class="form-label">Фамилия</label>
                    <input type="text" class="form-control" name="surname" id="surname" />
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="email" />
                </div>
                <button type="submit" class="btn btn-primary" id="find_user">
                    Найти
                </button>
            </x-api-search-form>
        </div>
        @if ($userInfoReceived)
            <div class="container" id="user_reviews">

                <div class="row mb-3 pb-3 border-bottom border-dark">
                    <div class="col-3">
                        <h5>{{ $user['name'] }} {{ $user['surname'] }}</h5>
                        <img src="{{ $user['profilePicture'] }}" alt="Аватарка пользователя"
                            style="width: 128px; heigth: 128px;" />
                    </div>

                    <div class="col-9">
                        <p>{{ $user['email'] }}</p>
                        <p>{{ $user['country'] }}</p>
                        <p>{{ $user['city'] }}</p>
                        <p>{{ $user['homeAdress'] }}</p>
                    </div>
                </div>

                <h5>Заказы пользователя {{ $user['name'] }} {{ $user['surname'] }}</h5>
                @foreach ($user['orders'] as $order)
                    <p>Заказ {{ $order['id'] }}</p>
                    <p>Сумма {{ $order['totalPrice'] }}</p>
                    <p>Дата осуществления заказа {{ $order['orderDate'] }}</p>
                    <p>Адрес доставки {{ $order['orderAdress'] }}</p>
                    <p>Состояние заказа {{ $order['orderStatus'] }}</p>
                    <p>Дата завершения заказа {{ $order['deleted_at'] }}</p>
                @endforeach
                <x-pagination :model="$userPaginatedOrders" />
            </div>
        @endif
    </div>
</x-admin-layout>
