<x-admin-layout>
    <x-slot name="title">
        Админ панель - Отзывы товара | Silerium
    </x-slot>
    <div class="container">
        <h1>Отзывы товара</h1>
        <x-search-form header="Поиск id товаров" submitId="find-products-id" :$queryInputs :$inputs />
        @if ($products != null)
            <p>
                <button class="btn" type="button" data-bs-toggle="collapse" data-bs-target="#foundProducts"
                    aria-expanded="false" aria-controls="foundProducts">
                    <i class="bi bi-arrow-down" id="foundProductsArrow"></i> Найденные товары
                </button>
            </p>
            <div class="collapse mb-3" id="foundProducts">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td>Id</td>
                            <td>Название</td>
                            <td>Отзывов</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                @php
                                    $product = (object) $product;
                                @endphp
                                <td>{{ $product->ulid }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ count($product->reviews) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <x-pagination :model="$products" />

                <form action="/admin/products/receive_product_reviews" method="POST" style="width: 300px;">
                    <h5>Показать отзывы товара</h5>
                    @csrf
                    <div class="mb-3">
                        <label for="id" class="form-label">Id товара</label>
                        <input type="text" class="form-control" name="id" id="id" />
                    </div>
                    <button type="submit" class="btn btn-primary">
                        Отправить
                    </button>
                </form>
            </div>
        @endif
        <p>
            <button class="btn" type="button" data-bs-toggle="collapse" data-bs-target="#foundReviews"
                aria-expanded="false" aria-controls="foundReviews">
                <i class="bi bi-arrow-down" id="foundReviewsArrow"></i> Отзывы товара
            </button>
        </p>
        <div class="collapse" id="foundReviews">
            @if ($reviews != null && $message == null)
                <ol>
                    @foreach ($reviews as $review)
                        @php
                            $review = (object)$review;
                            $review->user = (object)$review->user;
                            $review->product = (object)$review->product;
                        @endphp
                        <li class="border-bottom border-dark">
                            <h5>Отзыв пользователя {{ $review->user->name }} {{ $review->user->surname }}</h5>
                            <p>Название отзыва: {{ $review->title }}</p>
                            <p>Плюсы: {{ $review->pros }}</p>
                            <p>Минусы: {{ $review->cons }}</p>
                            <p>Комментарий: {{ $review->comment }}</p>
                            <p>Оценка: {{ $review->rating }}</p>
                            <p>Создан: {{ $review->created_at }}</p>
                            <p>Изменен: {{ $review->updated_at }}</p>
                            <p>Отзыв на товар: {{ $review->product->name }}</p>
                        </li>
                    @endforeach
                </ol>
                <x-pagination :model="$reviews" />
            @else
                <span class="mt-3">{{$message}}</span>
            @endif
        </div>

        <script type="module">
            $(document).ready(function() {
                let foundProducts = document.getElementById('foundProducts');
                foundProducts.addEventListener('show.bs.collapse', function() {
                    $('#foundProductsArrow').removeClass("bi-arrow-down");
                    $('#foundProductsArrow').addClass("bi-arrow-up");
                });
                foundProducts.addEventListener('hide.bs.collapse', function() {
                    $('#foundProductsArrow').removeClass("bi-arrow-up");
                    $('#foundProductsArrow').addClass("bi-arrow-down");
                });
                let productReviews = document.getElementById('foundReviews');
                productReviews.addEventListener('show.bs.collapse', function() {
                    $('#foundReviewsArrow').removeClass("bi-arrow-down");
                    $('#foundReviewsArrow').addClass("bi-arrow-up");
                });
                productReviews.addEventListener('hide.bs.collapse', function() {
                    $('#foundReviewsArrow').removeClass("bi-arrow-up");
                    $('#foundReviewsArrow').addClass("bi-arrow-down");
                });
            });
        </script>
    </div>
</x-admin-layout>
