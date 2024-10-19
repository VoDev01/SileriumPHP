<?php

namespace ComponentsInputs\SearchForm;

class SearchFormInputs
{
    public string $inputName;
    public string $displayName;
    public string $inputId;
    public $inputValue;
    public string $errorField;
    public bool $required;

    public function __construct(
        string $inputName,
        string $displayName,
        string $inputId = null,
        $inputValue = null,
        string $errorField,
        bool $required
    )
    {
        $this->inputName = $inputName;
        $this->displayName = $displayName;
        $this->inputId = $inputId;
        $this->inputValue = $inputValue;
        $this->errorField = $errorField;
        $this->required = $required;
    }
}