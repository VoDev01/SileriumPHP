<?php

namespace App\View\Components\ComponentsInputs\SearchForm;

class SearchFormQueryInput
{
    public string $searchActionUrl;
    public ?string $loadWith;
    public string $redirect;
    public mixed $searchKey;

    public function __construct(string $searchActionUrl, string $redirect = '#', mixed $loadWith = null, string $searchKey = null)
    {
        $this->searchActionUrl = $searchActionUrl;
        $this->loadWith = $loadWith;
        $this->redirect = $redirect;
        $this->searchKey = $searchKey;
    }
}