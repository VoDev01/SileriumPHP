<x-admin-layout>
    <x-slot name="title">
        Админ панель - Товары | Silerium
    </x-slot>
    <h1 class="text-center">Удаление товара</h1>
    <div class="container">
        <form class="mb-3">
            <div class="mb-3">
                <label for="name" class="form-label">Название товара</label>
                <input type="text" class="form-control" name="name" id="name" />
            </div>
            <div class="mb-3">
                <label for="id" class="form-label">Id товара</label>
                <input type="text" class="form-control" name="id" id="id" required />
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
        <form action="/admin/products/post_deleted_product" id="post_product_delete" hidden>
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
                let name = $("#name").val();
                let id = $("#id").val();
                if (id) {
                    $.ajax({
                        method: "GET",
                        url: "/admin/product/" + id + "/" + name,
                        dataType: "json",
                        success: function(data) {
                            $("#product_info").attr("hidden", false);
                            $('#product_not_found').attr("hidden", true);
                            $("#product_info_row").empty();
                            $('#post_product_delete').attr("hidden", false);
                            $("#product_info_row").append('<td>' + data.product.id + '</td>');
                            $("#product_info_row").append('<td>' + data.product.name + '</td>');
                            $("#product_info_row").append('<td>' + data.product.description + '</td>');
                            $("#product_info_row").append('<td>' + data.product.priceRub + '</td>');
                            $("#product_info_row").append('<td>' + data.product.stockAmount + '</td>');
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
                    $('#post_product_delete').attr("hidden", true);
                    $('#product_not_found').attr("hidden", false);
                }
            });
        });
    </script>
</x-admin-layout>
