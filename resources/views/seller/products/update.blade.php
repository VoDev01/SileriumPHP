<x-seller-layout>
    <x-slot name="title">
        Товары | Silerium Partner
    </x-slot>

    <div class="container">
        <h1>Изменить товар</h1>
        <x-search-form header="Поиск id товаров" submitId="find-products-id" :$queryInputs :$inputs />
        @if ($products != null)
            <p>
                <button class="btn" type="button" data-bs-toggle="collapse" data-bs-target="#foundProducts"
                    aria-expanded="false" aria-controls="foundProducts">
                    <i class="bi bi-arrow-down" id="foundProductsArrow"></i> Найденные товары
                </button>
            </p>
            <div class="collapse" id="foundProducts">
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
                @php
                    $searchKey = ['searchKey' => session('searchKey')];
                @endphp
                <x-pagination :model="$products" :params="$searchKey" />
            </div>
            <p>
                <button class="btn" type="button" data-bs-toggle="collapse" data-bs-target="#productEdit"
                    aria-expanded="false" aria-controls="productEdit">
                    <i class="bi bi-arrow-down" id="productEditArrow"></i> Редактирование товара
                </button>
            </p>
            <div class="collapse" id="productEdit">
                <form action="/seller/products/update" method="POST" id="update-form">
                    @csrf
                    <div class="mb-3">
                        <label for="id" class="form-label">Id</label>
                        <input type="text" class="form-control" name="id" id="id" />
                    </div>
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
                        <input class="form-check-input" type="checkbox" value="1" id="available" name="available" checked/>
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
                    <div class="mb-3" id="subcategory-margin" hidden>
                        <label for="subcategory" class="form-label">Подкатегория</label>
                        <select class="form-select form-select-lg" name="subcategory" id="subcategory"></select>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        Изменить
                    </button>
                </form>
            </div>
            <script type="module">
                $(document).ready(function() {
                    $('#category').on('change', function() {
                        let categoryId = $(this).val();
                        if (categoryId) {
                            $('#subcategory').empty();
                            $.ajax({
                                method: "GET",
                                url: "/seller/category/" + categoryId + "/subcategories",
                                dataType: "json",
                                success: function(data) {
                                    console.log(data);
                                    $('#subcategory-margin').attr('hidden', false);
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
                            $('#subcategory-margin').attr('hidden', true);
                        }
                    });
                    $('#available').on('click', function(){
                        if($('#available').val() == 0)
                            $('#available').val(1);
                        else
                            $('#available').val(0);
                    });
                    let foundProducts = document.getElementById('foundProducts');
                    foundProducts.addEventListener('show.bs.collapse', function() {
                        $('#foundProductsArrow').removeClass("bi-arrow-down");
                        $('#foundProductsArrow').addClass("bi-arrow-up");
                    });
                    foundProducts.addEventListener('hide.bs.collapse', function() {
                        $('#foundProductsArrow').removeClass("bi-arrow-up");
                        $('#foundProductsArrow').addClass("bi-arrow-down");
                    });
                    let productEdit = document.getElementById('productEdit');
                    productEdit.addEventListener('show.bs.collapse', function() {
                        $('#productEditArrow').removeClass("bi-arrow-down");
                        $('#productEditArrow').addClass("bi-arrow-up");
                    });
                    productEdit.addEventListener('hide.bs.collapse', function() {
                        $('#productEditArrow').removeClass("bi-arrow-up");
                        $('#productEditArrow').addClass("bi-arrow-down");
                    });
                });
            </script>
        @endif
    </div>
</x-seller-layout>
