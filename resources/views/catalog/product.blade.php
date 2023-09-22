@extends('layouts.layout')

@section('title')
    Товар
@endsection

@section('content')
<div class="container-fluid w-75">
    <h1 class="mb-3">{{$product->name}}</h1>
    <div id="productCarousel" class="carousel slide carousel-fade w-100 d-md-block d-none mb-3" data-bs-ride="carousel">
        <div class="carousel-indicators bg-opacity-25 bg-black mx-auto" style="width: 150px;">
            @for ($i = 0; $i < count($product->productImages()->get()); $i++)
                @if($i == 0)
                    <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                @else
                    <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="{{$i+1}}" aria-label="Slide {{$i+1}}"></button>
                @endif
            @endfor
        </div>
        <div class="carousel-inner">
            @for ($i = 0; $i < count($product->productImages()->get()); $i++) 
                @if($i == 0)
                    <div class="carousel-item active">
                        <img class="d-block w-100" src="{{asset($product->productImages()->first()->imagePath)}}" alt="Картинка товара 1" width="500" height="650">
                    </div>
                @else
                    <div class="carousel-item">
                        <img class="d-block w-100" src="{{asset($product->productImages()->first()->imagePath)}}" alt="Картинка товара {{$i+1}}" width="500" height="650">
                    </div>
                @endif
            @endfor
        </div>
    </div>
    <p>Цена в рублях: {{$product->priceRub}} &#8381;</p>
    <p>Количество - {{$product->stockAmount}} шт.</p>
    <p>Можно купить - {{$product->available ? "Да" : "Нет"}}</p>
    <div class="row">
        <div class="col-6">            
            <a class="btn btn-success">Добавить в корзину</a>
        </div>
        <div class="col-6">
            <a class="btn btn-success">Купить сразу</a>
        </div>
    </div>
</div>
@endsection