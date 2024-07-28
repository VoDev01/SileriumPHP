<a class="btn btn-success" data-bs-toggle="collapse" href="#filter_form" aria-expanded="false" aria-controls="filter_form">
    <i class="bi bi-funnel-fill"></i>
</a>
<div class="collapse" id="filter_form">
    <nav class="navbar navbar-expand-md">
        <div class="container-fluid g-3">
            <span class="navbar-text">Сортировать по:</span>
            <div class="navbar-collapse">
                <div class="navbar-nav">
                    @switch($sortOrder)
                        @case(App\Enum\SortOrder::NAME_ASC->value)
                            <a class="nav-link active text-black"
                                href="{{ $filterActionLink . App\Enum\SortOrder::NAME_DESC->value . $filterActionParams }}">
                                Названию
                                <i class="bi bi-arrow-down text-secondary"></i>
                                <i class="bi bi-arrow-up"></i>
                            </a>
                            @if ($popularity)
                                <a class="nav-link text-black"
                                    href="{{ $filterActionLink . App\Enum\SortOrder::POP_DESC->value . $filterActionParams }}">
                                    Популярности
                                    <i class="bi bi-arrow-down text-secondary"></i>
                                    <i class="bi bi-arrow-up text-secondary"></i>
                                </a>
                            @endif
                            @if ($price)
                                <a class="nav-link text-black"
                                    href="{{ $filterActionLink . App\Enum\SortOrder::PRICE_DESC->value . $filterActionParams }}">
                                    Цене
                                    <i class="bi bi-arrow-down text-secondary"></i>
                                    <i class="bi bi-arrow-up text-secondary"></i>
                                </a>
                            @endif
                        @break

                        @case(App\Enum\SortOrder::NAME_DESC->value)
                            <a class="nav-link active text-black"
                                href="{{ $filterActionLink . App\Enum\SortOrder::NAME_ASC->value . $filterActionParams }}">
                                Названию
                                <i class="bi bi-arrow-down"></i>
                                <i class="bi bi-arrow-up text-secondary"></i>
                            </a>
                            @if ($popularity)
                                <a class="nav-link text-black"
                                    href="{{ $filterActionLink . App\Enum\SortOrder::POP_DESC->value . $filterActionParams }}">
                                    Популярности
                                    <i class="bi bi-arrow-down text-secondary"></i>
                                    <i class="bi bi-arrow-up text-secondary"></i>
                                </a>
                            @endif
                            @if ($price)
                                <a class="nav-link text-black"
                                    href="{{ $filterActionLink . App\Enum\SortOrder::PRICE_DESC->value . $filterActionParams }}">
                                    Цене
                                    <i class="bi bi-arrow-down text-secondary"></i>
                                    <i class="bi bi-arrow-up text-secondary"></i>
                                </a>
                            @endif
                        @break

                        @case(App\Enum\SortOrder::POP_ASC->value)
                            <a class="nav-link text-black"
                                href="{{ $filterActionLink . App\Enum\SortOrder::NAME_DESC->value . $filterActionParams }}">
                                Названию
                                <i class="bi bi-arrow-down text-secondary"></i>
                                <i class="bi bi-arrow-up text-secondary"></i>
                            </a>
                            @if ($popularity)
                                <a class="nav-link active text-black"
                                    href="{{ $filterActionLink . App\Enum\SortOrder::POP_DESC->value . $filterActionParams }}">
                                    Популярности
                                    <i class="bi bi-arrow-down text-secondary"></i>
                                    <i class="bi bi-arrow-up"></i>
                                </a>
                            @endif
                            @if ($price)
                                <a class="nav-link text-black"
                                    href="{{ $filterActionLink . App\Enum\SortOrder::PRICE_DESC->value . $filterActionParams }}">
                                    Цене
                                    <i class="bi bi-arrow-down text-secondary"></i>
                                    <i class="bi bi-arrow-up text-secondary"></i>
                                </a>
                            @endif
                        @break

                        @case(App\Enum\SortOrder::POP_DESC->value)
                            <a class="nav-link text-black"
                                href="{{ $filterActionLink . App\Enum\SortOrder::NAME_DESC->value . $filterActionParams }}">
                                Названию
                                <i class="bi bi-arrow-down text-secondary"></i>
                                <i class="bi bi-arrow-up text-secondary"></i>
                            </a>
                            @if ($popularity)
                                <a class="nav-link active text-black"
                                    href="{{ $filterActionLink . App\Enum\SortOrder::POP_DESC->value . $filterActionParams }}">
                                    Популярности
                                    <i class="bi bi-arrow-down"></i>
                                    <i class="bi bi-arrow-up text-secondary"></i>
                                </a>
                            @endif
                            @if ($price)
                                <a class="nav-link text-black"
                                    href="{{ $filterActionLink . App\Enum\SortOrder::PRICE_DESC->value . $filterActionParams }}">
                                    Цене
                                    <i class="bi bi-arrow-down text-secondary"></i>
                                    <i class="bi bi-arrow-up text-secondary"></i>
                                </a>
                            @endif
                        @break

                        @case(App\Enum\SortOrder::PRICE_ASC->value)
                            <a class="nav-link text-black"
                                href="{{ $filterActionLink . App\Enum\SortOrder::NAME_DESC->value . $filterActionParams }}">
                                Названию
                                <i class="bi bi-arrow-down text-secondary"></i>
                                <i class="bi bi-arrow-up text-secondary"></i>
                            </a>
                            @if ($popularity)
                                <a class="nav-link text-black"
                                    href="{{ $filterActionLink . App\Enum\SortOrder::POP_DESC->value . $filterActionParams }}">
                                    Популярности
                                    <i class="bi bi-arrow-down text-secondary"></i>
                                    <i class="bi bi-arrow-up text-secondary"></i>
                                </a>
                            @endif
                            @if ($price)
                                <a class="nav-link active text-black"
                                    href="{{ $filterActionLink . App\Enum\SortOrder::PRICE_DESC->value . $filterActionParams }}">
                                    Цене
                                    <i class="bi bi-arrow-down text-secondary"></i>
                                    <i class="bi bi-arrow-up"></i>
                                </a>
                            @endif
                        @break

                        @case(App\Enum\SortOrder::PRICE_DESC->value)
                            <a class="nav-link text-black"
                                href="{{ $filterActionLink . App\Enum\SortOrder::NAME_DESC->value . $filterActionParams }}">
                                Названию
                                <i class="bi bi-arrow-down text-secondary"></i>
                                <i class="bi bi-arrow-up text-secondary"></i>
                            </a>
                            @if ($popularity)
                                <a class="nav-link text-black"
                                    href="{{ $filterActionLink . App\Enum\SortOrder::POP_DESC->value . $filterActionParams }}">
                                    Популярности
                                    <i class="bi bi-arrow-down text-secondary"></i>
                                    <i class="bi bi-arrow-up text-secondary"></i>
                                </a>
                            @endif
                            @if ($price)
                                <a class="nav-link active text-black"
                                    href="{{ $filterActionLink . App\Enum\SortOrder::PRICE_ASC->value . $filterActionParams }}">
                                    Цене
                                    <i class="bi bi-arrow-down"></i>
                                    <i class="bi bi-arrow-up text-secondary"></i>
                                </a>
                            @endif
                        @break

                    @endswitch
                    @if ($currency)
                        <form action="{{ $filterRubCurrencyLink }}" method="POST">
                            @csrf
                            <button type="submit" class="btn">&#8381;</button>
                        </form>
                        <form action="{{ $filterDolCurrencyLink }}" method="POST">
                            @csrf
                            <button type="submit" class="btn">&#36;</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </nav>
    {{ $slot }}
</div>
