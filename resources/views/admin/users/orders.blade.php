<x-admin-layout>
    <x-slot name="title">
        Админ панель - Заказы пользователя | Silerium
    </x-slot>
    <div class="container">
        <div class="d-flex">
            @php
                $inputs = array(
                    array('inputName' => 'name', 'displayName' => 'Имя'),
                    array('inputName' => 'surname', 'displayName' => 'Фамилия'),
                    array('inputName' => 'email', 'displayName' => 'Email', 'required' => true)
                )
            @endphp
            <x-api-search-form actionUrl="/admin/users/post_user_search" loadWith="reviews" redirect="user_reviews" header="Поиск пользователей" submit_id="find_user" :$inputs/>
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
