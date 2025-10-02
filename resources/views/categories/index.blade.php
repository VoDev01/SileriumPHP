<x-layout>

    <x-slot name="title">
        Категории | Silerium
    </x-slot>
    <div class="mx-3">
        <h1>Категории</h1>
        <div class="row">
            @foreach ($categories as $category)
                <div class="col col-sm-12 col-md-6 col-xl-4">
                    <a class="d-inline-block text-decoration-none text-center text-black"
                        href="/categories/{{ $category->id }}/subcategories">
                        <img class="category-image" src="{{ $category->image }}" alt="{{ $category->name }}">
                        <p>{{ $category->name }}</p>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</x-layout>
