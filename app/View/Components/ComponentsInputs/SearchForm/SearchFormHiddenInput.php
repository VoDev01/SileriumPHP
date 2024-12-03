<?php

namespace App\View\Components\ComponentsInputs\SearchForm;

class SearchFormHiddenInput
{
    public string $inputName;
    public string $inputId;
    public $inputValue;

    public function __construct(
        string $inputName,
        string $inputId,
        $inputValue
    )
    {
        $this->inputName = $inputName;
        $this->inputId = $inputId;
        $this->inputValue = $inputValue;
    }
}
