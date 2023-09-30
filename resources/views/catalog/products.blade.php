<x-layout>

    <x-slot name="title">
        Товары | Silerium
    </x-slot>

    <script type="text/javascript">
        $(".select1").change(function() {
            if ($(this).data('options') == undefined) {
                /*Taking an array of all options-2 and kind of embedding it on the select1*/
                $(this).data('options', $('.select2 option').clone());
            }
            var id = $(this).val();
            var options = $(this).data('options').filter('[data-name=' + id + ']');
            $('.select2').html(options);
        });
    </script>

    <div class="mx-3">
        <h1>Товары</h1>
        <nav class="navbar navbar-expand-md">
            <div class="container-fluid g-3">
                <span class="navbar-text">Сортировать по:</span>
                <div class="navbar-collapse">
                    <div class="navbar-nav">
                        @php
                            $productParam = $product == null ? '' : '/' . $product;
                            $orderLink = '/catalog/products/';
                            $orderParams = '/' . $available . '/' . $subcategory . $productParam;
                        @endphp
                        @switch($sortOrder)
                            @case(App\Enum\SortOrder::NAME_ASC->value)
                                <a class="nav-link active text-black"
                                    href="{{ $orderLink . App\Enum\SortOrder::NAME_DESC->value . $orderParams }}">
                                    Названию
                                    <i class="bi bi-arrow-down text-secondary"></i>
                                    <i class="bi bi-arrow-up"></i>
                                </a>
                                <a class="nav-link text-black"
                                    href="{{ $orderLink . App\Enum\SortOrder::POP_DESC->value . $orderParams }}">
                                    Популярности
                                    <i class="bi bi-arrow-down text-secondary"></i>
                                    <i class="bi bi-arrow-up text-secondary"></i>
                                </a>
                                <a class="nav-link text-black"
                                    href="{{ $orderLink . App\Enum\SortOrder::PRICE_DESC->value . $orderParams }}">
                                    Цене
                                    <i class="bi bi-arrow-down text-secondary"></i>
                                    <i class="bi bi-arrow-up text-secondary"></i>
                                </a>
                            @break

                            @case(App\Enum\SortOrder::NAME_DESC->value)
                                <a class="nav-link active text-black"
                                    href="{{ $orderLink . App\Enum\SortOrder::NAME_ASC->value . $orderParams }}">
                                    Названию
                                    <i class="bi bi-arrow-down"></i>
                                    <i class="bi bi-arrow-up text-secondary"></i>
                                </a>
                                <a class="nav-link text-black"
                                    href="{{ $orderLink . App\Enum\SortOrder::POP_DESC->value . $orderParams }}">
                                    Популярности
                                    <i class="bi bi-arrow-down text-secondary"></i>
                                    <i class="bi bi-arrow-up text-secondary"></i>
                                </a>
                                <a class="nav-link text-black"
                                    href="{{ $orderLink . App\Enum\SortOrder::PRICE_DESC->value . $orderParams }}">
                                    Цене
                                    <i class="bi bi-arrow-down text-secondary"></i>
                                    <i class="bi bi-arrow-up text-secondary"></i>
                                </a>
                            @break

                            @case(App\Enum\SortOrder::POP_ASC->value)
                                <a class="nav-link text-black"
                                    href="{{ $orderLink . App\Enum\SortOrder::NAME_DESC->value . $orderParams }}">
                                    Названию
                                    <i class="bi bi-arrow-down text-secondary"></i>
                                    <i class="bi bi-arrow-up text-secondary"></i>
                                </a>
                                <a class="nav-link active text-black"
                                    href="{{ $orderLink . App\Enum\SortOrder::POP_DESC->value . $orderParams }}">
                                    Популярности
                                    <i class="bi bi-arrow-down text-secondary"></i>
                                    <i class="bi bi-arrow-up"></i>
                                </a>
                                <a class="nav-link text-black"
                                    href="{{ $orderLink . App\Enum\SortOrder::PRICE_DESC->value . $orderParams }}">
                                    Цене
                                    <i class="bi bi-arrow-down text-secondary"></i>
                                    <i class="bi bi-arrow-up text-secondary"></i>
                                </a>
                            @break

                            @case(App\Enum\SortOrder::POP_DESC->value)
                                <a class="nav-link text-black"
                                    href="{{ $orderLink . App\Enum\SortOrder::NAME_DESC->value . $orderParams }}">
                                    Названию
                                    <i class="bi bi-arrow-down text-secondary"></i>
                                    <i class="bi bi-arrow-up text-secondary"></i>
                                </a>
                                <a class="nav-link active text-black"
                                    href="{{ $orderLink . App\Enum\SortOrder::POP_ASC->value . $orderParams }}">
                                    Популярности
                                    <i class="bi bi-arrow-down" href="{{ $orderLink }}"></i>
                                    <i class="bi bi-arrow-up text-secondary"></i>
                                </a>
                                <a class="nav-link text-black"
                                    href="{{ $orderLink . App\Enum\SortOrder::PRICE_DESC->value . $orderParams }}">
                                    Цене
                                    <i class="bi bi-arrow-down text-secondary"></i>
                                    <i class="bi bi-arrow-up text-secondary"></i>
                                </a>
                            @break

                            @case(App\Enum\SortOrder::PRICE_ASC->value)
                                <a class="nav-link text-black"
                                    href="{{ $orderLink . App\Enum\SortOrder::NAME_DESC->value . $orderParams }}">
                                    Названию
                                    <i class="bi bi-arrow-down text-secondary"></i>
                                    <i class="bi bi-arrow-up text-secondary"></i>
                                </a>
                                <a class="nav-link text-black"
                                    href="{{ $orderLink . App\Enum\SortOrder::POP_DESC->value . $orderParams }}">
                                    Популярности
                                    <i class="bi bi-arrow-down text-secondary"></i>
                                    <i class="bi bi-arrow-up text-secondary"></i>
                                </a>
                                <a class="nav-link active text-black"
                                    href="{{ $orderLink . App\Enum\SortOrder::PRICE_DESC->value . $orderParams }}">
                                    Цене
                                    <i class="bi bi-arrow-down text-secondary"></i>
                                    <i class="bi bi-arrow-up"></i>
                                </a>
                            @break

                            @case(App\Enum\SortOrder::PRICE_DESC->value)
                                <a class="nav-link text-black"
                                    href="{{ $orderLink . App\Enum\SortOrder::NAME_DESC->value . $orderParams }}">
                                    Названию
                                    <i class="bi bi-arrow-down text-secondary"></i>
                                    <i class="bi bi-arrow-up text-secondary"></i>
                                </a>
                                <a class="nav-link text-black"
                                    href="{{ $orderLink . App\Enum\SortOrder::POP_DESC->value . $orderParams }}">
                                    Популярности
                                    <i class="bi bi-arrow-down text-secondary"></i>
                                    <i class="bi bi-arrow-up text-secondary"></i>
                                </a>
                                <a class="nav-link active text-black"
                                    href="{{ $orderLink . App\Enum\SortOrder::PRICE_ASC->value . $orderParams }}">
                                    Цене
                                    <i class="bi bi-arrow-down"></i>
                                    <i class="bi bi-arrow-up text-secondary"></i>
                                </a>
                            @break
                        @endswitch
                        <form action="/catalog/rubcurrency" method="POST">
                            @csrf
                            <button type="submit" class="btn">&#8381;</button>
                        </form>
                        <form action="/catalog/dolcurrency" method="POST">
                            @csrf
                            <button type="submit" class="btn">&#36;</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
        <div class="container-fluid mb-5" style="width: 97%;">
            <div class="row flex-column flex-lg-row">
                <div class="col-12 col-lg-9">
                    @empty($products)
                        <p class="text-secondary">По вашему запросу товаров не было найдено.</p>
                    @endempty
                    @foreach ($products as $product)
                        <a href="/catalog/product/{{ $product->id }}" class="row text-decoration-none text-black">
                            <div class="col">
                                <h3> {{ $product->name }}</h3>

                                @if ($product->images->first() == null)
                                    <img src="#" class="col" alt="картинка товара" width="250"
                                        height="250">
                                @else
                                    <img src="{{ asset($product->images->first()->imagePath) }}" class="col"
                                        alt="картинка товара" width="250" height="250">
                                @endif
                            </div>
                            @php
                                $symb = Session::exists('products_currency') ? session('currency_symb') : '&#8381;';
                            @endphp

                            <div class="row col flex-column">
                                <p class="col">{{ $product->priceRub }} {{ $symb }}</p>
                                <div class="col">
                                    <p>{{ $product->stockAmount <= 0 ? 'Нет в наличии' : $product->stockAmount . ' шт.' }}
                                    </p>
                                </div>
                                <div class="col">
                                    <p>{{ $product->available ? 'В наличии' : 'Нет в наличии' }}</p>
                                </div>
                            </div>
                        </a>
                        <hr/>
                    @endforeach
                </div>
                <div class="col-12 col-lg-3 border border-1 p-3">
                    <p>Фильтрация и поиск товаров</p>
                    <form action="/catalog/filter" method="POST">
                        @csrf
                        <label for="category" class="form-label select1">Категория</label>
                        <select id="category" name="category" class="form-select">
                            <option value="all">Все</option>
                            @foreach ($categories as $category)
                                <option data-name="{{ $category->id }}" value="{{ $category->id }}">
                                    {{ $category->name }}</option>
                            @endforeach
                        </select>
                        <label for="subcategory" class="form-label">Подкатегория</label>
                        <select id="subcategory" name="subcategory" class="form-select select2">
                            <option value="all" hidden>Все</option>
                            @foreach ($subcategories as $subcategory)
                                <option data-name="{{ $subcategory->name }}" value="{{ $subcategory->id }}">
                                    {{ $subcategory->name }}</option>
                            @endforeach
                        </select>
                        <label for="product" class="form-label">Название товара</label>
                        <input type="text" name="product" id="product" class="form-control" />
                        <label for="available" class="form-label">Доступен к продаже</label>
                        <input hidden name="available" value="0" />
                        @if ($available == 0)
                            <input type="checkbox" name="available" id="available" value="1"
                                class="form-check" />
                        @else
                            <input type="checkbox" name="available" id="available" value="1"
                                class="form-check" checked />
                        @endif
                        <input hidden name="sort_order" value="{{ $sortOrder }}" />
                        <button type="submit" class="btn btn-success mt-3">Фильтровать</button>
                    </form>
                </div>
            </div>
        </div>
        <x-pagination :model="$products" />
    </div>
</x-layout>
