<?php

namespace App\View\Components\ComponentsMethods\SearchForm;

use Illuminate\Http\Request;

interface SearchFormInterface
{
    /**
     * Defines how to search a model
     *
     * @param Request $request Some additional request data
     * @param array $validated Validated request
     * @return void
     */
    public static function search(Request $request, array $validated);
}