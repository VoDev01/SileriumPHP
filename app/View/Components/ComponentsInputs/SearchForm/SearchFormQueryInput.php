<?php

namespace App\View\Components\ComponentsInputs\SearchForm;

/**
 * Url query parameters of the SearchForm
 */
class SearchFormQueryInput
{
    public string $searchActionUrl;
    public ?string $loadWith;
    public string $redirect;

    /**
     * @param string $searchActionUrl Form action url
     * @param string $redirect Redirect back url
     * @param mixed $loadWith Load model with relation|s
     */
    public function __construct(string $searchActionUrl, string $redirect = '#', mixed $loadWith = null)
    {
        $this->searchActionUrl = $searchActionUrl;
        $this->loadWith = $loadWith;
        $this->redirect = $redirect;
    }
}