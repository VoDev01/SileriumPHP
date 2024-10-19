<?php

namespace ComponentsInputs\SearchForm;

class SearchFormHiddenInputs
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
