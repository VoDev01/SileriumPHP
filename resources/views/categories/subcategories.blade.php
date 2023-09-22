@extends('layouts.layout')
@section('content')

    @section('title')
        Подкатегории 
    @endsection

    <div class="row mx-3">
        <h1>Подкатегории</h1>
        @isset($subcategories)
            @foreach ($subcategories as $subcategory)
                <div class="col col-sm-12 col-md-6 col-xl-4">
                    @if ($subcategory->image != null)
                        <a class="d-inline-block text-decoration-none text-center text-black" href="/catalog/products">
                            <img class="subcategory-image" src="{{asset($subcategory->image)}}" alt="{{$subcategory->name}}">
                            <br>
                            {{$subcategory->name}}
                        </a>
                    @else
                        <a class="d-inline-block text-decoration-none text-center text-black" href="/catalog/products">
                            <br>
                            {{$subcategory->name}}
                        </a>
                    @endif
                </div>
            @endforeach
        @endisset
        @empty($subcategories)
            <p class="text-danger">Нет подкатегории!</p>
        @endempty
    </div>

@endsection