<x-layout>
    <x-slot name="title">
        Редактировать отзыв | Silerium
    </x-slot>
    <div class="container-fluid row flex-column mx-3">
        <h1 class="col">{{$product->name}}</h1>
        <img class="col" src="{{asset($product->images->first()->imagePath)}}" alt="Картинка товара">
        <form class="col" action="/user/edit_review" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="rating" class="form-label">Оценка</label>
                <select class="form-select form-select-sm star-rating" name="rating" id="rating">
                    <option value="5" selected></option>
                    <option value="4"></option>
                    <option value="3"></option>
                    <option value="2"></option>
                    <option value="1"></option>
                </select>
            </div>
            <div class="mb-3">
              <label for="title" class="form-label">Заголовок</label>
              <input type="text"
                class="form-control" name="title" id="title" placeholder="Отзыв о товаре {{$product->name}}">
                <x-error field="title"/>
            </div>
            <div class="mb-3">
              <label for="pros" class="form-label">Плюсы</label>
              <textarea class="form-control" name="pros" id="pros" rows="10"></textarea>
              <x-error field="pros"/>
            </div>
            <div class="mb-3">
              <label for="cons" class="form-label">Минусы</label>
              <textarea class="form-control" name="cons" id="cons" rows="10"></textarea>
              <x-error field="cons"/>
            </div>
            <div class="mb-3">
              <label for="comment" class="form-label">Комментарий</label>
              <textarea class="form-control" name="comment" id="comment" rows="10"></textarea>
              <x-error field="comment"/>
            </div>
            <div class="mb-3">
              <label for="reviewImages" class="form-label">Изображения к отзыву</label>
              <input type="file" class="form-control" name="reviewImages" id="reviewImages" multiple>
              <x-error field="reviewImages"/>
            </div>
            <input hidden name="productId" value="{{$product->id}}">
            <input hidden name="reviewId" value="{{$review->id}}">
            <button type="submit" class="btn btn-primary">Отправить</button>
        </form>
    </div>
</x-layout>