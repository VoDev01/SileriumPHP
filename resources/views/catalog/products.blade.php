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
            let id = $(this).val();
            let options = $(this).data('options').filter('[data-name=' + id + ']');
            $('.select2').html(options);
        });
    </script> --}}

    <div class="mx-3">
        @php
            $filterActionParams = '/' . $available . '/' . $subcategory;
        @endphp
        <div class="container-fluid mt-2">
            <x-filter-form filterActionLink="/catalog/products/" :$filterActionParams
                filterRubCurrencyLink="/catalog/rub_currency" filterDolCurrencyLink="/catalog/dol_currency" :$sortOrder
                popularity="true" price="true" currency="true">

                <x-search-form header="Поиск товаров" submit_id="searchProducts" :$queryInputs :$inputs :$hiddenInputs
                    :$checkboxInputs />

            </x-filter-form>
            <div class="row flex-column flex-lg-row">
                <div class="col-12 col-lg-9">
                    @if (empty($products))
                        <p class="text-secondary">По вашему запросу товаров не найдено</p>
                    @else
                        @foreach ($products as $product)
                            <?php
                            $product = (object) $product;
                            $image = $product->images->first() ?? null;
                            ?>
                            <a href="/catalog/product/{{ $product->id }}" class="row text-decoration-none text-black">
                                <div class="col">
                                    <h3> {{ $product->name }}</h3>

                                    @if ($image === null)
                                        <img src="#" class="col" alt="картинка товара" width="256"
                                            height="256">
                                    @else
                                        <img src="{{ $image->imagePath }}" class="col" alt="картинка товара"
                                            width="256" height="256">
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
                                        <p>{{ $product->productAmount . ' шт.' }}
                                        </p>
                                    </div>
                                    <div class="col">
                                        <p>{{ $product->available ? 'В наличии' : 'Нет в наличии' }}</p>
                                    </div>
                                </div>
                            </a>
                            <hr>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        @if (!empty($products))
            <x-pagination :model="$products" />
        @endif
    </div>
</x-layout>
