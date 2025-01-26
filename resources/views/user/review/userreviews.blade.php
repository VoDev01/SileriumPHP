<x-layout>
    <x-slot name="title">
        Ваши отзывы | Silerium
    </x-slot>
    <div class="container m-auto">
        <h1>Ваши отзывы</h1>
        @forelse($reviews as $review)
            <div class="row flex-column mb-5">
                <h5 class="col">{{ $review->title }}</h5>
                <div class="row col">
                    <div class="row col">
                        @if ($review->updatedAt != null)
                            <p class="col-2">{{ $review->createdAt->format('Y-m-d') }}</p>
                        @else
                            <p class="col-2">Ред. {{ $review->updatedAt->format('Y-m-d') }}</p>
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
                        <p>{{ $review->comment }}</p>
                    </div>
                @else
                    <div class="col">
                        <h6>Комментарий</h6>
                        <p class="text-secondary">Нет комментария</p>
                    </div>
                @endempty
                <div class="row col">
                    <div class="col-1">
                        <a class="btn btn-outline-warning" href="/user/edit_review/{{$review->id}}">
                            <i class="bi bi-arrow-repeat"></i>
                        </a>
                    </div>
                    <form action="/user/delete_review" class="form-inline col-1" method="post">
                        @csrf
                        <input hidden name="review_id" value="{{$review->id}}">
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-secondary">Нет отзывов.</p>
        @endforelse
    </div>
    <x-pagination :model="$reviews" />
</x-layout>
