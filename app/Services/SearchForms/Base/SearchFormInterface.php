<?php

namespace App\Services\SearchForms\Base;

use Illuminate\Http\Request;

interface SearchFormInterface
{
    /**
     * Defines how to search the model
     *
     * @param array $validated Validated request
     * @param string $responseName Data name in json response
     * @param string $notFoundMessage Message that will be returned if no data was found
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function search(array $validated, string $responseName = 'response', ?string $redirect = null, ?string $notFoundMessage = null) : \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse;
}