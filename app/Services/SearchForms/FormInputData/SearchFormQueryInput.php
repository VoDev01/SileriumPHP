<?php

namespace App\Services\SearchForms\FormInputData;

/**
 * Url query parameters of the SearchForm
 */
class SearchFormQueryInput
{

    /**
     * @param string $searchActionUrl Form action url
     * @param mixed $loadWith Load model with relation|s
     */
    public function __construct(
        public string $searchActionUrl,
        public mixed $loadWith = null
    )
    {}
}
