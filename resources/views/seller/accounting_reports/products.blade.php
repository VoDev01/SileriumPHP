<x-seller-layout>
    <x-slot name="title">
        Отчетность товаров | Silerium Partner
    </x-slot>
    <!--<style>
        @php
            include(public_path().'/css/bootstrap.min.css');
        @endphp
    </style>-->
    <style>
        tr {
            border: 1px solid gray;
            padding: 5px;
        }
        td {
            border: 1px solid gray;
            padding: 5px;
        }
        th {
            border: 1px solid gray;
            padding: 5px;
        }
    </style>
    <h1>Бухгалтерские отчености товаров</h1>
    <x-search-form header="Поиск товаров" submit_id="searchProducts" :$queryInputs :$inputs />
    @if ($products != null)
        <div class="table-responsive">
            <table style="border: 2px solid black; margin-bottom: 15px; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Id</th>
                        <th>Название товара</th>
                        <th>Кол-во.</th>
                        <th>Ед.</th>
                        <th>Цена</th>
                        <th>Сумма</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 0;
                    @endphp
                    @foreach ($products as $product)
                        <tr>
                            @php
                                $i++;
                                $product = (object) $product;
                            @endphp
                            <td>{{ $i }}</td>
                            <td>{{ $product->ulid }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->productAmount }}</td>
                            <td>Ед.</td>
                            <td>{{ $product->priceRub }}</td>
                            <td>{{ $product->priceRub * $product->productAmount }}</td>
                            <td><a class="text-decoration-none btn btn-primary"
                                    href="/seller/accounting_reports/product/{{ $product->ulid }}">Отчет</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <x-pagination :model="$products" />
        </div>
    @endif
    <form action="/seller/accounting_reports/format/pdf" method="POST">
        @csrf
        <input hidden type="text" class="form-control" name="pageHtml" id="pageHtml" value="" />
        <button type="submit" id="formatButton" class="btn btn-primary">
            Форматировать
        </button>
    </form>
    <script type="module">
        $(document).ready(function() {
            let html = document.createElement('html');
            html.innerHTML = '<html>' + document.head.innerHTML + document.body.innerHTML + '</html>';

            let navbar = html.getElementsByClassName('navbar');
            while (navbar[0]) {
                navbar[0].parentNode.removeChild(navbar[0]);
            }
            let form = html.getElementsByTagName('form');
            while (form[0]) {
                form[0].parentNode.removeChild(form[0]);
            }
            let link = html.getElementsByTagName('a');
            while (link[0]) {
                link[0].parentNode.removeChild(link[0]);
            }
            let tr = html.getElementsByTagName('tr');
            $(tr).each(function(){

                $(this).children('td').each(function(){
                    if(this.innerHTML === '')
                    {
                        this.remove();
                    }
                });

                $(this).children('th').each(function(){
                    if(this.innerHTML === '')
                    {
                        this.remove();
                    }
                });
            });
            let head = html.getElementsByTagName('head')[0];
            /*let foorter = html.getElementsByTagName('footer');
            while(footer[0])
            {
                footer[0].parentNode.removeChild(footer[0]);
            }*/

            console.log(html.outerHTML);
            $('#formatButton').on('click', function() {
                $('#pageHtml').val(html.outerHTML);
            });
            
        });
    </script>
</x-seller-layout>
