<x-layout>

    <x-slot name="title">
       Магазиг электронной техники маркетплейс | Silerium
    </x-slot>

    <div id="homeCarousel" class="carousel slide carousel-fade w-100 d-md-block d-none" data-bs-ride="carousel">
        <div class="carousel-indicators bg-opacity-25 bg-black mx-auto" style="width: 150px;">
            <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <a class="d-block" href="/categories/{{$categories['smartphones']['model']->id}}/subcategories">
                    <img class="d-block w-100" src="{{$categories['smartphones']['image']}}" alt="Смартфоны">
                </a>
                <div class="carousel-caption d-none d-lg-block text-white">
                    <h3>Смартфоны</h3>
                </div>
            </div>
            <div class="carousel-item">
                <a class="d-block" href="/categories/{{$categories['hardware']['model']->id}}/subcategories">
                    <img class="d-block w-100" src="{{$categories['hardware']['image']}}" alt="Комплектующие ПК">
                </a>
                <div class="carousel-caption d-none d-lg-block text-white">
                    <h3>Комплектующие ПК</h3>
                </div>
            </div>
            <div class="carousel-item">
                <a class="d-block" href="/categories/{{$categories['monitors']['model']->id}}/subcategories">
                    <img class="d-block w-100" src="{{$categories['monitors']['image']}}" alt="Мониторы и телевизоры">
                </a>
                <div class="carousel-caption d-none d-lg-block text-black">
                    <h3>Мониторы и телевизоры</h3>
                </div>
            </div>
            <div class="carousel-item">
                <a class="d-block" href="/categories/{{$categories['laptops']['model']->id}}/subcategories">
                    <img class="d-block w-100" src="{{$categories['laptops']['image']}}" alt="Ноутбуки">
                </a>
                <div class="carousel-caption d-none d-lg-block text-black">
                    <h3>Ноутбуки</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-0 bg-black">
        <div class="col-sm-12 col-md-5 col-lg-6 text-white d-flex justify-content-center flex-column px-2 ad-text">
            <h3 style="font-size: 55px;">
                Скидки
            </h3>
            <p style="font-size: 36px;">
                Успейте купить все товары
                <br />
                по выгодной цене!
            </p>
        </div>
        <div class="col-sm-12 col-md-7 col-lg-6">
            <img class="d-block" src="{{$categories[0]}}" alt="Скидки" style="height: 800px; width: 100%;" />
        </div>
    </div>
    <a class="btn rounded-pill btn-outline-dark m-auto d-block mt-2 category-btn" href="/categories/all">Перейти к покупке</a>
</x-layout>