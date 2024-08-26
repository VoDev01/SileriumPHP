<x-layout>

    <x-slot name="title">
        Товары | Silerium
    </x-slot>

    {{-- <script type="module">
        $(".select1").change(function() {
            if ($(this).data('options') == undefined) {
                /*Taking an array of all options-2 and kind of embedding it on the select1*/
                $(this).data('options', $('.select2 option').clone());
            }
            var id = $(this).val();
            var options = $(this).data('options').filter('[data-name=' + id + ']');
            $('.select2').html(options);
        });
    </script> --}}

    <div class="mx-3">
        @php
            $inputs = [['displayName' => 'Название товара', 'inputName' => 'name', 'field' => 'name', 'required' => false]];
            $checkboxInputs = [['displayName' => 'В продаже', 'inputId' => 'available', 'inputName' => 'available', 'required' => false]];
            $hiddenInputs = [
                ['inputName' => 'sortOrder', 'inputId' => 'sortOrder', 'inputValue' => $sortOrder],
                ['inputName' => 'available', 'inputId' => null, 'inputValue' => 0],
                ['inputName' => 'subcategory', 'inputId' => 'subcategory', 'inputValue' => $subcategory],
            ];
            $filterActionParams = '/' . $available . '/' . $subcategory;
        @endphp
        <div class="container-fluid mt-2">
            <x-filter-form filterActionLink="/catalog/products/" :$filterActionParams
                filterRubCurrencyLink="/catalog/rubcurrency" filterDolCurrencyLink="/catalog/dolcurrency" :$sortOrder
                popularity="true" price="true" currency="true">

                <x-search-form searchActionUrl="/catalog/filter" header="Поиск товаров"
                    loadWith="images, productSpecifications" :$hiddenInputs :$inputs :$checkboxInputs
                    submit_id="filterProducts" />

            </x-filter-form>
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
                                    <img src="#" class="col" alt="картинка товара" width="128"
                                        height="256">
                                @else
                                    <img src="{{ asset($product->images->first()->imagePath) }}" class="col"
                                        alt="картинка товара" width="128" height="256">
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
