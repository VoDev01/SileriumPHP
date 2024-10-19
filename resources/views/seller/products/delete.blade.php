<x-seller-layout>
    <x-slot name="title">
       Товары | Silerium Partner
    </x-slot>
    <h1 class="text-center">Удаление товара</h1>
    <div class="container">
        <form class="mb-3">
            <input id="seller_name" val="{{session('seller_name')}}" hidden />
            <div class="mb-3">
                <label for="product_name" class="form-label">Название товара</label>
                <input type="text" class="form-control" name="product_name" id="product_name" required/>
            </div>
            <p class="text-danger" hidden id="product_not_found">Товар не найден</p>
            <button type="submit" class="btn btn-primary" id="submit_product_name">
                Найти
            </button>
        </form>
        <table class="table table-bordered mb-3" id="product_info" hidden>
            <thead>
                <tr>
                    <td>Id</td>
                    <td>Название</td>
                    <td>Описание</td>
                    <td>Цена в рублях</td>
                    <td>Количество</td>
                    <td>В продаже</td>
                    <td>Подкатегория</td>
                    <td>Куплено раз</td>
                </tr>
            </thead>
            <tbody>
                <tr id="product_info_row">
                </tr>
            </tbody>
        </table>
        <form action="/seller/products/deleted_product" id="product_delete" hidden>
            @csrf
            <input hidden name="id" value="{{$product->id}}" />
            <button type="submit" class="btn btn-danger">
                Удалить
            </button>
        </form>

    </div>
    <script type="module">
        $(document).ready(function() {
            $("#submit_product_name").on("click", function(e) {
                e.preventDefault();
                let product_name = $("#product_name").val();
                let seller_name = $("#seller_name").val();
                if (seller_name && product_name) {
                    $.ajax({
                        method: "GET",
                        url: "/seller/product/" + seller_name + "/" + product_name,
                        dataType: "json",
                        success: function(data) {
                            $("#product_info").attr("hidden", false);
                            $('#product_not_found').attr("hidden", true);
                            $("#product_info_row").empty();
                            $('#product_delete').attr("hidden", false);
                            $("#product_info_row").append('<td>' + data.product.id + '</td>');
                            $("#product_info_row").append('<td>' + data.product.name + '</td>');
                            $("#product_info_row").append('<td>' + data.product.description + '</td>');
                            $("#product_info_row").append('<td>' + data.product.priceRub + '</td>');
                            $("#product_info_row").append('<td>' + data.product.productAmount + '</td>');
                            $("#product_info_row").append('<td>' + data.product.available + '</td>');
                            $("#product_info_row").append('<td>' + data.subcategoryName +
                                '</td>');
                            $("#product_info_row").append('<td>' + data.product.timesPurchased +
                                '</td>');
                        }
                    });
                } else {
                    $("#product_info").attr("hidden", true);
                    $("#product_info_row").empty();
                    $('#product_delete').attr("hidden", true);
                    $('#product_not_found').attr("hidden", false);
                }
            });
        });
    </script>
</x-seller-layout>
