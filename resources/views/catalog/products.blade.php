<x-layout>

    <x-slot name="title">
        Товары | Silerium
    </x-slot>

<<<<<<< HEAD
    {{-- <script type="module">
=======
    <script type="module">
>>>>>>> admin_panel
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
<<<<<<< HEAD
            $inputs = [['displayName' => 'Название товара', 'inputName' => 'name', 'field' => 'name', 'required' => false]];
            $checkboxInputs = [['displayName' => 'В продаже', 'inputId' => 'available', 'inputName' => 'available', 'required' => false]];
            $hiddenInputs = [
                ['inputName' => 'sortOrder', 'inputId' => 'sortOrder', 'inputValue' => $sortOrder],
                ['inputName' => 'available', 'inputId' => null, 'inputValue' => 0],
=======
            $inputs = [['displayName' => 'Название товара', 'inputName' => 'name', 'required' => true]];
            $checkboxInputs = [['displayName' => 'В продаже', 'inputName' => 'available', 'required' => false]];
            $hiddenInputs = [
                ['inputName' => 'sortOrder', 'inputId' => 'sortOrder', 'inputValue' => $sortOrder],
                ['inputName' => 'available', 'inputId' => 'available', 'inputValue' => $available],
>>>>>>> admin_panel
                ['inputName' => 'subcategory', 'inputId' => 'subcategory', 'inputValue' => $subcategory],
            ];
            $filterActionParams = '/' . $available . '/' . $subcategory;
        @endphp
        <div class="container-fluid mt-2">
            <x-filter-form filterActionLink="/catalog/products/" :$filterActionParams
                filterRubCurrencyLink="/catalog/rubcurrency" filterDolCurrencyLink="/catalog/dolcurrency" :$sortOrder
                popularity="true" price="true" currency="true">
<<<<<<< HEAD

                <x-search-form searchActionUrl="/catalog/filter" header="Поиск товаров"
                    loadWith="images, productSpecifications" :$hiddenInputs :$inputs :$checkboxInputs
                    submit_id="filterProducts" />

=======
                <x-search-form searchActionUrl="/catalog/filter" header="Поиск товаров"
                    loadWith="images, productSpecifications" :$hiddenInputs :$inputs :$checkboxInputs
                    submit_id="filterProducts" />
>>>>>>> admin_panel
            </x-filter-form>
            <div class="row flex-column flex-lg-row">
                <div class="col-12 col-lg-9">
                    @if ($products->isEmpty())
                        <p class="text-secondary">По вашему запросу товаров не было найдено.</p>
                    @endif
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

                            <div class="row col flex-column">
                                <p class="col">{{ $product->priceRub }}
                                    @if (session('products_currency') == 'rub')
                                        &#8381;
                                    @elseif (session('products_currency') == 'dol')
                                        &#36;
                                    @elseif(session('products_currency') == null)
                                        &#8381;
                                    @endif
                                </p>
                                <div class="col">
                                    <p>{{ $product->stockAmount <= 0 ? 'Нет в наличии' : $product->stockAmount . ' шт.' }}
                                    </p>
                                </div>
                                <div class="col">
                                    <p>{{ $product->available ? 'В наличии' : 'Нет в наличии' }}</p>
                                </div>
                            </div>
                        </a>
                        <hr />
                    @endforeach
                </div>
            </div>
        </div>
        <x-pagination :model="$products" />
    </div>
</x-layout>
