<x-seller-layout>
    <x-slot name="title">
        Отзывы товара | Silerium Partner
    </x-slot>
    <div class="container">
        
        @php
            $inputs = array(
                array('inputName' => 'product_name', 'displayName' => 'Название товара', 'field' = 'product_name', 'required' => true),
                array('inputName' => 'seller_name', 'displayName' => 'Название продавца', 'field' = 'seller_name', 'required' => true)
            )
        @endphp
        <x-api-search-form actionUrl="/admin/users/product_search" loadWith="reviews" redirect="product_reviews" header="Поиск отзывов на товар" submit_id="find_review" :$inputs/>
        @if ($productInfoReceived)
            <div class="container border border-dark" id="product_reviews">
                <h5>{{ $user['name'] }} {{ $user['surname'] }}</h5>
                <img src="{{ $user['profilePicture'] }}" alt="Аватарка пользователя"
                    style="width: 128px; heigth: 128px;" />
                <p>{{ $user['email'] }}</p>
                <p>{{ $user['country'] }}</p>
                <p>{{ $user['city'] }}</p>
                <p>{{ $user['homeAdress'] }}</p>

                <th></th>

                <h5>Отзывы пользователя {{ $user['name'] }} {{ $user['surname'] }}</h5>
                @foreach ($user['reviews'] as $review)
                    <p>Название отзыва: {{ $review['title'] }}</p>
                    <p>Плюсы: {{ $review['pros'] }}</p>
                    <p>Минусы: {{ $review['cons'] }}</p>
                    <p>Комментарий: {{ $review['comment'] }}</p>
                    <p>Оценка: {{ $review['rating'] }}</p>
                    <p>Создан: {{ $review['createdAt'] }}</p>
                    <p>Изменен: {{ $review['updatedAt'] }}</p>
                    <p>Отзыв на товар: {{ $review['product']['name'] }}</p>
                @endforeach
                <x-pagination :model="$userPaginatedReviews" />
            </div>
        @endif
    </div>
</x-seller-layout>
