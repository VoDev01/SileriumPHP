<x-seller-layout>
    <x-slot name="title">
        Отзывы товара | Silerium Partner
    </x-slot>
    <div class="container">
        <h1>Отзывы товара</h1>
        <x-search-form header="Поиск id товаров" submitId="find-products-id" :$queryInputs :$inputs />
        @php
            $searchKey = ['searchKey' => session('searchKey')];
        @endphp
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
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <x-pagination :model="$products" :params="$searchKey" />

                <form action="/seller/products/receive_product_reviews" method="POST" style="width: 300px;">
                    <h5>Показать отзывы товара</h5>
                    @csrf
                    <input name="searchKey" value="{{ session('searchKey') }}" hidden />
                    <div class="mb-3">
                        <label for="productId" class="form-label">Id товара</label>
                        <input type="text" class="form-control" name="productId" id="productId" />
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
            @if ($reviews != null)
                @foreach ($reviews as $review)
                    <div class="container border border-dark mb-3" id="product_reviews">
                        @php
                            $review = (object) $review;
                            $review->user = (object) $review->user;
                            $review->product = (object) $review->product;
                        @endphp
                        <h5>Отзыв пользователя {{ $review->user->name }} {{ $review->user->surname }}</h5>
                        <p>Название отзыва: {{ $review->title }}</p>
                        <p>Плюсы: {{ $review->pros }}</p>
                        <p>Минусы: {{ $review->cons }}</p>
                        <p>Комментарий: {{ $review->comment }}</p>
                        <p>Оценка: {{ $review->rating }}</p>
                        <p>Создан: {{ $review->created_at }}</p>
                        <p>Изменен: {{ $review->updated_at }}</p>
                        <p>Отзыв на товар: {{ $review->product->name }}</p>
                    </div>
                @endforeach
                <x-pagination :model="$reviews" :params="$searchKey" />
            @else
                <p>У данного товара пока что нет отзывов.</p>
            @endif
        </div>

        <script type="module">
            $(document).ready(function() {
                var foundProducts = document.getElementById('foundProducts');
                foundProducts.addEventListener('show.bs.collapse', function() {
                    $('#foundProductsArrow').removeClass("bi-arrow-down");
                    $('#foundProductsArrow').addClass("bi-arrow-up");
                });
                foundProducts.addEventListener('hide.bs.collapse', function() {
                    $('#foundProductsArrow').removeClass("bi-arrow-up");
                    $('#foundProductsArrow').addClass("bi-arrow-down");
                });
                var productReviews = document.getElementById('foundReviews');
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
</x-seller-layout>
