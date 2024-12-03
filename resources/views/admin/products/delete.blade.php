<x-admin-layout>
    <x-slot name="title">
        Админ панель - Товары | Silerium
    </x-slot>
    
    <div class="container">
        <h1>Удаление</h1>
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
                <button class="btn" type="button" data-bs-toggle="collapse" data-bs-target="#productDelete"
                    aria-expanded="false" aria-controls="productDelete">
                    <i class="bi bi-arrow-down" id="productDeleteArrow"></i> Удаление товара
                </button>
            </p>
            <div class="collapse" id="productDelete">
                <form action="/admin/products/delete" method="POST" id="product_delete" style="width: 300px">
                    @csrf
                    <div class="mb-3">
                        <label for="id" class="form-label">Id</label>
                        <input type="text" class="form-control" name="id" id="id" />
                    </div>
                    <button type="submit" class="btn btn-danger">
                        Удалить
                    </button>
                </form>
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
                    var productDelete = document.getElementById('productDelete');
                    productDelete.addEventListener('show.bs.collapse', function() {
                        $('#productDeleteArrow').removeClass("bi-arrow-down");
                        $('#productDeleteArrow').addClass("bi-arrow-up");
                    });
                    productDelete.addEventListener('hide.bs.collapse', function() {
                        $('#productDeleteArrow').removeClass("bi-arrow-up");
                        $('#productDeleteArrow').addClass("bi-arrow-down");
                    });
                });
            </script>
        @endif
    </div>
</x-admin-layout>
