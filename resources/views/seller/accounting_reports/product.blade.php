<x-seller-layout>
    <x-slot name="title">
        Отченость товара | Silerium Partner
    </x-slot>

    <link rel="stylesheet" type="text/css" href="{{ asset('css/pdf-style.css') }}" />

    @if (!isset($data->lowerDate) && !isset($data->upperDate) && !isset($data->year))
        <h2>Отчет о розничных продажах товара {{ $product->ulid }}</h2>
        <h4>Укажите отрезок дат для составления отчета</h4>
        <form action="/seller/accounting_reports/product" method="POST">
            @csrf
            <div>
                <label for="lowerDate">С</label>
                <input type="text" min="4" max="5" name="lowerDate" id="lowerDate" class="daypicker" />

                <label for="upperDate">По</label>
                <input type="text" min="4" max="5" name="upperDate" id="upperDate" class="daypicker" />

                <label for="year">Года</label>
                <input type="text" min="3" max="5" name="year" id="year" class="yearpicker" />
            </div>

            <input hidden name="ulid" value="{{ $product->ulid }}" />
            <div class="mt-3">
                <button type="submit" class="btn btn-primary" class="mt-3">Составить отчет</button>
            </div>
        </form>
    @else
        @if ($product === null)
            <h3>Нет данных за указанный промежуток</h3>
        @else
            <h3>Отчет о розничных продажах товара {{ $product->ulid }}</h3>
            <h4>С {{ $data->lowerDate }} по {{ $data->upperDate }} число {{ $data->year }} года</h4>
            <div id="seller-products">
                <table>
                    <thead>
                        <th>№</th>
                        <th>Id</th>
                        <th>Название</th>
                        <th>Кол-во</th>
                        <th>Кол-во продаж</th>
                        <th>Ед.</th>
                        <th>Цена</th>
                        <th>Доходы</th>
                        <th>Валюта</th>
                        <th>Израсход. через</th>
                    </thead>
                    <tbody>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->ulid }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->productAmount }}</td>
                        <td>{{ $data->sellAmount ?? 0 }}</td>
                        <td>Шт.</td>
                        <td>{{ $product->priceRub }}</td>
                        <td>{{ $data->income ?? 0 }}</td>
                        <td>Руб.</td>
                        <td>{{ $data->expiry ?? "никогда" }}</td>
                    </tbody>
                </table>
            </div>
            <form action="/format/pdf" method="POST" id="submitForm">
                @csrf
                <input hidden type="text" name="pageHtml" id="pageHtml" />
                <input hidden type="text" name="tableHtml" id="tableHtml" />
                <input hidden type="text" name="tableRowHtml" id="tableRowHtml" />
                <input hidden type="text" name="insertAfterElement" id="insertAfterElement" />
                <input hidden name="data[]" id="data" />
                <button type="submit" id="formatButton" class="btn btn-primary">
                    Форматировать
                </button>
                <x-all-errors />
            </form>
        @endif
    @endif
    @vite(['resources/js/prepare-page-for-pdf-formatting.js', 'resources/js/hide-datepicker.js'])
</x-seller-layout>
