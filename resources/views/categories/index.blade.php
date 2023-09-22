<x-layout>

    <x-slot name="title">
        Категории
    </x-slot>
    <div class="mx-3">
        <h1>Категории</h1>
        <div class="row">
            @foreach($categories as $category)
                <div class="col col-sm-12 col-md-6 col-xl-4">
                    @if($category->image != null)
                        <a class="d-inline-block text-decoration-none text-center text-black" href="/categories/{{$category->id}}/subcategories">
                            <img class="category-image" src="{{asset($category->image)}}" alt="{{$category->name}}">
                            <br>
                            {{$category->name}}
                        </a>
                    @else
                        <a class="d-inline-block text-decoration-none text-center text-black" href="/categories/{{$category->id}}/subcategories">
                            <br>
                            {{$category->name}}
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</x-layout>