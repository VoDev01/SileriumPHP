<x-admin-layout>
    <x-slot name="title">
        Админ панель - Отзывы товара | Silerium
    </x-slot>
    <div class="container">
        @php
            $inputs = [
                new SearchFormInputs('sellerNickname', 'Название продавца', 'seller_nickname', true),
                new SearchFormInputs('productName', 'Название товара', 'product_name', true),
            ];
            $queryInputs = new SearchFormQueryInputs('/admin/products/product_search', 'reviews', 'product_reviews');
        @endphp
        <x-search-form header="Поиск отзывов на товар" submit_id="find_product_review" :$queryInputs :$inputs />
        <h1>Отзывы товара</h1>
        <table class="table table-bordered mb-3" id="review_info" hidden>
            <thead>
                <td>Название отзыва</td>
                <td>Плюсы</td>
                <td>Минусы</td>
                <td>Комментарий</td>
                <td>Оценка</td>
                <td>Создан</td>
                <td>Изменен</td>
                <td>Отзыв на товар</td>
            </thead>
            <tbody>
                <tr id="review_info_row">
                </tr>
            </tbody>
        </table>
        <x-pagination :model="$userPaginatedReviews" />
    </div>
    <script type="module">
        $(document).ready(function() {
            $("#find_product_review").on("click", function(e) {
                e.preventDefault();
                let product_name = $("#product_name").val();
                let seller_name = $("#seller_name").val();
                if (seller_name && product_name) {
                    $.ajax({
                        method: "GET",
                        url: "/admin/product/search_reviews/" + seller_name + "/" + product_name,
                        dataType: "json",
                        success: function(data) {
                            $("#review_info").attr("hidden", false);
                            $("#review_info_row").empty();
                            $("#review_info_row").append('<td>' + data.review.id + '</td>');
                            $("#review_info_row").append('<td>' + data.review.name + '</td>');
                            $("#review_info_row").append('<td>' + data.review.pros +
                                '</td>');
                            $("#review_info_row").append('<td>' + data.review.cons +
                                '</td>');
                            $("#review_info_row").append('<td>' + data.review.comment +
                                '</td>');
                            $("#review_info_row").append('<td>' + data.review.rating +
                                '</td>');
                            $("#review_info_row").append('<td>' + data.review.created +
                                '</td>');
                            $("#review_info_row").append('<td>' + data.review.updated +
                                '</td>');
                            $("#review_info_row").append('<td>' + data.review.productName +
                                '</td>');
                        }
                    });
                } else {
                    $("#review_info").attr("hidden", true);
                    $("#review_info_row").empty();
                }
            });
        });
    </script>
</x-admin-layout>
