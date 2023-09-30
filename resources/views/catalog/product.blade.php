<x-layout>

    <x-slot name="title">
        Товар {{$product->name}} | Silerium
    </x-slot>
    <div class="container-fluid m-auto w-75">
        <h1 class="mb-3">{{ $product->name }}</h1>
        <div id="productCarousel" class="carousel slide carousel-fade w-100 d-md-block d-none mb-3"
            data-bs-ride="carousel">
            <div class="carousel-indicators bg-opacity-25 bg-black mx-auto" style="width: 150px;">
                @for ($i = 0; $i < $product->images->count(); $i++)
                    @if ($i == 0)
                        <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="0" class="active"
                            aria-current="true" aria-label="Slide 1"></button>
                    @else
                        <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="{{ $i + 1 }}"
                            aria-label="Slide {{ $i + 1 }}"></button>
                    @endif
                @endfor
            </div>
            <div class="carousel-inner">
                @for ($i = 0; $i < $product->images->count(); $i++)
                    @if ($i == 0)
                        <div class="carousel-item active">
                            <img class="d-block w-100" src="{{ asset($product->images->first()->imagePath) }}"
                                alt="Картинка товара 1" width="300" height="300">
                        </div>
                    @else
                        <div class="carousel-item">
                            <img class="d-block w-100" src="{{ asset($product->images->first()->imagePath) }}"
                                alt="Картинка товара {{ $i + 1 }}" width="300" height="300">
                        </div>
                    @endif
                @endfor
            </div>
        </div>
        <div class="row">
            <div class="row col-4 flex-column">
                <div class="col w-100">
                    <h4>Описание</h4>
                    <p>{{ $product->description }}</p>
                </div>
                <div class="col w-100">
                    <h6>Характеристики</h6>
                    @foreach($product->productSpecifications as $spec)
                        <p>{{ $spec->name }}: {{ $spec->specification }}</p>
                    @endforeach
                </div>
            </div>
            <div class="col-4"></div>
            @auth
                @if ($product->available)
                    <div class="row col-4 flex-column shadow rounded pt-5" style="width: 350px;">
                        <div class="row col flex-column">
                            <p class="col text-white border rounded-pill text-center"
                                style="font-size: 2em; background: #1ac748; width: 200px;">{{ $product->priceRub }} &#8381;
                            </p>
                            <p class="col text-secondary">
                                {{ $product->stockAmount . ' шт.' }}
                            </p>
                            <p class="col text-secondary">{{ $product->available ? 'В наличии' : 'Нет в наличии' }}</p>
                        </div>
                        <div class="row col">
                            <div class="col">
                                <a class="btn btn-outline-success" href="/catalog/addtocart/{{ $product->id }}">В
                                    корзину</a>
                            </div>
                            <div class="col">
                                <a class="btn btn-outline-danger">Купить сразу</a>
                            </div>
                        </div>
                    </div>
                @endif
            @endauth
            @guest
                <div class="col-4 shadow rounded pt-5">
                    <p class="text-secondary">Если вы хотите преобрести товар войдите в свой личный кабинет.</p>
                    <a class="btn btn-outline-primary" href="/user/login" role="button">Войти</a>
                </div>
            @endguest
        </div>
        <h4>Отзывы</h4>
        @auth
            <a class="btn btn-outline-primary mt-3 mb-5" href="/user/review/product/{{$product->id}}">Написать отзыв</a>
        @endauth
        @forelse($reviews as $review)
            <div class="row flex-column broder border-bottom">
                <h5 class="col">{{ $review->title }}</h5>
                <div class="row col">
                    <div class="row col">
                        <div class="col-1">
                            <img src="{{asset($review->user->profilePicture)}}"
                                width="100" height="100" alt="Картинка профиля">
                        </div>
                        <p class="col-2">{{ $review->user->name }} {{ $review->user->surname }}</p>
                        @if ($review->updatedAt != null)
                            <p class="col-1">{{ $review->createdAt->format('Y-m-d') }}</p>
                        @else
                            <p class="col-1">Ред. {{ $review->updatedAt->format('Y-m-d') }}</p>
                        @endif
                        <div class="col-2">
                            @for ($i = 0; $i < $review->rating; $i++)
                                <i class="bi bi-star-fill" style="color: #ffc800;"></i>
                            @endfor
                            @for ($j = 0; $j < 5 - $review->rating; $j++)
                                <i class="bi bi-star-fill" style="color: #969696;"></i>
                            @endfor
                        </div>
                    </div>
                </div>
                <div class="col">
                    <h6>Плюсы</h6>
                    <p>{{ $review->pros }}</p>
                </div>
                <div class="col">
                    <h6>Минусы</h6>
                    <p>{{ $review->cons }}</p>
                </div>
                @empty($review->comment)
                    <div class="col">
                        <h6>Комментарий</h6>
                        <p class="text-secondary">Нет комментария</p>
                    </div>
                @else
                    <div class="col">
                        <h6>Комментарий</h6>
                        <p>{{ $review->comment }}</p>
                    </div>
                @endempty
            </div>
        @empty
            <p class="text-secondary">У данного товара нет отзывов.</p>
        @endforelse
    </div>
    <x-pagination :model="$reviews" />
</x-layout>
