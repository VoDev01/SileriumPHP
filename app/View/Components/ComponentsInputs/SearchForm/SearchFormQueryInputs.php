<?php

namespace ComponentsInputs\SearchForm;

class SearchFormQueryInputs
{
    public string $searchActionUrl;
    public string $loadWith;
    public string $redirect;

    public function __construct(string $searchActionUrl, string $loadWith = '', string $redirect = '#')
    {
        $this->$searchActionUrl = $searchActionUrl;
        $this->$loadWith = $loadWith;
        $this->$redirect = $redirect;
    }
}