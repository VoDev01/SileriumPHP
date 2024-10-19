<x-seller-layout>
    <x-slot name="title">
        Товары | Silerium Partner
    </x-slot>
    <h1 class="text-center">Изменить товар</h1>
    <div class="container">
        <form action="/admin/products/updated_product" method="POST" hidden id="update_form">
            @csrf
            <input hidden name="id" value="{{ $product->id }}" />
            <div class="mb-3">
                <label for="name" class="form-label">Название</label>
                <input type="text" class="form-control" name="name" id="name" />
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Описание</label>
                <input type="text" class="form-control" name="description" id="description" />
            </div>
            <div class="mb-3">
                <label for="priceRub" class="form-label">Цена в рублях</label>
                <input type="text" class="form-control" name="priceRub" id="priceRub" />
            </div>
            <div class="mb-3">
                <label for="productAmount" class="form-label">Количество товара к продаже</label>
                <input type="text" class="form-control" name="productAmount" id="productAmount" />
            </div>
            <div class="mb-3">
                <label class="form-check-label" for="available"> Доступен к продаже </label>
                <input class="form-check-input" type="checkbox" value="0" id="available" />
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Категория</label>
                <select class="form-select form-select-lg" name="category" id="category">
                    <option selected disabled>Выберите категорию</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3" id="subcategory_margin" hidden>
                <label for="subcategory" class="form-label">Подкатегория</label>
                <select class="form-select form-select-lg" name="subcategory" id="subcategory"></select>
            </div>
            <input name="seller_id" value="{{session("seller_id")}}" hidden/>
            <button type="submit" class="btn btn-primary">
                Изменить
            </button>
            <script type="module">
                $(document).ready(function() {
                    $('#category').on('change', function() {
                        let categoryId = $(this).val();
                        if (categoryId) {
                            $('#subcategory').empty();
                            $.ajax({
                                method: "GET",
                                url: "/admin/category/" + categoryId + "/subcategories",
                                dataType: "json",
                                success: function(data) {
                                    console.log(data);
                                    $('#subcategory_margin').attr('hidden', false);
                                    $('#subcategory').append(
                                        '<option selected disabled>Выберите подкатегорию</option>');
                                    $.each(data.subcategories, function(id, val) {
                                        $('#subcategory').append('<option value="' + id + '">' +
                                            val.name + '</option>');
                                    });
                                }
                            });
                        } else {
                            $('#subcategory').empty();
                            $('#subcategory_margin').attr('hidden', true);
                        }
                    });
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
                                    $("#update_form").attr("hidden", false);
                                    $("#product_not_found").attr("hidden", false);
                                }
                            });
                        } else {
                            $("#update_form").attr("hidden", true);
                            $("#product_not_found").attr("hidden", true);
                        }
                    });
                });
            </script>
        </form>
    </div>
</x-seller-layout>
