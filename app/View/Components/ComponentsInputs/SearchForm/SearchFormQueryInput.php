<?php

namespace App\View\Components\ComponentsInputs\SearchForm;

class SearchFormQueryInput
{
    public string $searchActionUrl;
    public ?string $loadWith;
    public string $redirect;

    public function __construct(string $searchActionUrl, string $redirect = '#', mixed $loadWith = null)
    {
        $this->searchActionUrl = $searchActionUrl;
        $this->loadWith = $loadWith;
        $this->redirect = $redirect;
    }
}