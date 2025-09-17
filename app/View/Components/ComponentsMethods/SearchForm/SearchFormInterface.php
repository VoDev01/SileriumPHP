<?php

namespace App\View\Components\ComponentsMethods\SearchForm;

use Illuminate\Http\Request;

interface SearchFormInterface
{
    public static function search(Request $request, array $validated);
}