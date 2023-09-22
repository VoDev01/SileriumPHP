<x-layout>

    <x-slot name="title">
        Товары
    </x-slot>
    
    <script type="text/javascript">
        $(".select1").change(function () {
            if ($(this).data('options') == undefined) {
                /*Taking an array of all options-2 and kind of embedding it on the select1*/
                $(this).data('options', $('.select2 option').clone());
            }
            var id = $(this).val();
            var options = $(this).data('options').filter('[data-name=' + id + ']');
            $('.select2').html(options);
        });
    </script>

    <div>
        <h1>Товары</h1>
        <nav class="navbar navbar-expand-md">
            <div class="container-fluid g-3">
                <span class="navbar-text">Сортировать по:</span>
                <div class="navbar-collapse">
                    <div class="navbar-nav">
                        @switch ($sortOrder)
                            @case(App\Enum\SortOrder::NAME_DESC)
                                <a class="nav-link active text-black">
                                    Названию
                                    <i class="bi bi-arrow-down text-secondary"></i>
                                    <i class="bi bi-arrow-up"></i>
                                </a>
                                <a class="nav-link text-black">
                                    Популярности
                                    <i class="bi bi-arrow-down text-secondary"></i>
                                    <i class="bi bi-arrow-up text-secondary"></i>
                                </a>
                                <a class="nav-link text-black">
                                    Цене
                                    <i class="bi bi-arrow-down text-secondary"></i>
                                    <i class="bi bi-arrow-up text-secondary"></i>
                                </a>
                                @break
                            @case(App\Enum\SortOrder::NAME_ASC)
                                <a class="nav-link active text-black">
                                    Названию
                                    <i class="bi bi-arrow-down"></i>
                                    <i class="bi bi-arrow-up text-secondary"></i>
                                </a>
                                <a class="nav-link text-black">
                                    Популярности
                                    <i class="bi bi-arrow-down text-secondary"></i>
                                    <i class="bi bi-arrow-up text-secondary"></i>
                                </a>
                                <a class="nav-link text-black">
                                    Цене
                                    <i class="bi bi-arrow-down text-secondary"></i>
                                    <i class="bi bi-arrow-up text-secondary"></i>
                                </a>
                                @break
                            @case(App\Enum\SortOrder::POP_ASC)
                                <a class="nav-link text-black">
                                    Названию
                                    <i class="bi bi-arrow-down text-secondary"></i>
                                    <i class="bi bi-arrow-up text-secondary"></i>
                                </a>
                                <a class="nav-link active text-black">
                                    Популярности
                                    <i class="bi bi-arrow-down text-secondary"></i>
                                    <i class="bi bi-arrow-up"></i>
                                </a>
                                <a class="nav-link text-black">
                                    Цене
                                    <i class="bi bi-arrow-down text-secondary"></i>
                                    <i class="bi bi-arrow-up text-secondary"></i>
                                </a>
                                @break
                            @case(App\Enum\SortOrder::POP_DESC)
                                <a class="nav-link text-black">
                                    Названию
                                    <i class="bi bi-arrow-down text-secondary"></i>
                                    <i class="bi bi-arrow-up text-secondary"></i>
                                </a>
                                <a class="nav-link active text-black">
                                    Популярности
                                    <i class="bi bi-arrow-down"></i>
                                    <i class="bi bi-arrow-up text-secondary"></i>
                                </a>
                                <a class="nav-link text-black">
                                    Цене
                                    <i class="bi bi-arrow-down text-secondary"></i>
                                    <i class="bi bi-arrow-up text-secondary"></i>
                                </a>
                                @break
                            @case(App\Enum\SortOrder::PRICE_ASC)
                                <a class="nav-link text-black">
                                    Названию
                                    <i class="bi bi-arrow-down text-secondary"></i>
                                    <i class="bi bi-arrow-up text-secondary"></i>
                                </a>
                                <a class="nav-link text-black">
                                    Популярности
                                    <i class="bi bi-arrow-down text-secondary"></i>
                                    <i class="bi bi-arrow-up text-secondary"></i>
                                </a>
                                <a class="nav-link active text-black">
                                    Цене
                                    <i class="bi bi-arrow-down text-secondary"></i>
                                    <i class="bi bi-arrow-up"></i>
                                </a>
                                @break
                            @case(App\Enum\SortOrder::PRICE_DESC)
                                <a class="nav-link text-black">
                                    Названию
                                    <i class="bi bi-arrow-down text-secondary"></i>
                                    <i class="bi bi-arrow-up text-secondary"></i>
                                </a>
                                <a class="nav-link text-black">
                                    Популярности
                                    <i class="bi bi-arrow-down text-secondary"></i>
                                    <i class="bi bi-arrow-up text-secondary"></i>
                                </a>
                                <a class="nav-link active text-black">
                                    Цене
                                    <i class="bi bi-arrow-down"></i>
                                    <i class="bi bi-arrow-up text-secondary"></i>
                                </a>
                                @break
                        @endswitch
                    </div>
                </div>
            </div>
        </nav>
        <div class="container-fluid mb-5" style="width: 97%;">
            <div class="row">
                <div class="col-9">
                    @empty($products)
                        <p class="text-secondary">По вашему запросу товаров не было найдено.</p>
                    @foreach ($products as $product)
                        <div>
                            <h3>{{$product->name}}</h3>
                            <img src="{{asset($product->productImages->first()->imagePath)}}" alt="картинка товара" width="250" height="250">
                            <p>Цена в рублях: {{$product->priceRub}} &#8381;</p>
                            <div class="row">
                                <div class="col-4">
                                    <p>Количество - {{$product->stockAmount}} шт.</p>
                                </div>
                                <div class="col-4">
                                    <p>Можно купить - {{$product->available ? "Да" : "Нет"}}</p>
                                </div>
                                <div class="col-4">
                                    <a class="btn btn-success" href="/catalog/product/{{$product->id}}">К товару</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="col-3 border border-1 p-3">
                    <p>Фильтрация и поиск товаров</p>
                    <form action="/catalog/filter" method="POST">
                        @csrf
                        <label for="category" class="form-label select1">Категория</label>
                        <select id="category" name="category" class="form-select">
                            <option value="all">Все</option>
                            @foreach ($categories as $category)
                                <option data-name="{{$category->id}}" value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                        </select>
                        <label for="subcategory" class="form-label">Подкатегория</label>
                        <select id="subcategory" name="subcategory" class="form-select select2">
                            <option value="all" hidden>Все</option>
                            @foreach ($subcategories as $subcategory)
                                <option data-name="{{$subcategory->name}}" value="{{$subcategory->id}}">{{$subcategory->name}}</option>
                            @endforeach
                        </select>
                        <label for="product" class="form-label">Название товара</label>
                        <input type="text" name="product" id="product" class="form-control" />
                        <label for="available" class="form-label">Доступен к продаже</label>
                        <input type="checkbox" name="available" id="available" class="form-check" checked />
                        <input hidden name="sort_order" value="{{$sortOrder}}"/>
                        <button type="submit" class="btn btn-success mt-3">Фильтровать</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>