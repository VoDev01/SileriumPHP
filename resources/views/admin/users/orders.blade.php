<x-admin-layout>
    <x-slot name="title">
        Админ панель - Заказы пользователя | Silerium
    </x-slot>
    <div class="container">
        <h1>Заказы пользователей</h1>
        <x-search-form header="Поиск пользователей" submit_id="find_user" :$inputs :$queryInputs />
        @if ($users != null)
            <p>
                <button class="btn" type="button" data-bs-toggle="collapse" data-bs-target="#foundUsers"
                    aria-expanded="false" aria-controls="foundUsers">
                    <i class="bi bi-arrow-down" id="foundUsersArrow"></i> Пользователи
                </button>
            </p>
            <div class="collapse" id="foundUsers">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td>Id</td>
                            <td>Имя</td>
                            <td>Фамилия</td>
                            <td>Email</td>
                            <td>Телефон</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            @php
                                $user = (object) $user;
                            @endphp
                            <tr>
                                <td>{{ $user->ulid }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->surname }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <x-pagination :model="$users"/>
            </div>
            <form action="/admin/products/orders" method="POST" style="width: 300px;">
                <h5>Показать заказы пользователя</h5>
                @csrf
                <input name="searchKey" value="{{ session('searchKey') }}" hidden />
                <div class="mb-3">
                    <label for="id" class="form-label">Id пользователя</label>
                    <input type="text" class="form-control" name="id" id="id" />
                </div>
                <button type="submit" class="btn btn-primary">
                    Отправить
                </button>
            </form>
            @if ($orders != null && $message == null)
                <h5>Заказы пользователя {{ $user->name }} {{ $user->surname }}</h5>
                @foreach ($orders as $order)
                    @php
                        $order = (object) $order;
                    @endphp
                    <table>
                        <thead>
                            <tr>
                                <td>Заказ</td>
                                <td>Сумма</td>
                                <td>Дата осуществления заказа</td>
                                <td>Адрес доставки</td>
                                <td>Состояние</td>
                                <td>Дата завершения заказа</td>
                                <td>Название товаров</td>
                            </tr>
                        </thead>
                        <tbody>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->totalPrice }}</td>
                            <td>{{ $order->orderDate }}</td>
                            <td>{{ $order->orderAdress }}</td>
                            <td>{{ $order->orderStatus }}</td>
                            <td>{{ $order->deleted_at }}</td>
                            <td>{{ $order->productsNames }}</td>
                        </tbody>
                    </table>
                @endforeach
                <x-pagination :model="$orders" />
            @else
                <span class="mt-3">{{ $message }}</span>
            @endif
        @endif
    </div>
</x-admin-layout>
