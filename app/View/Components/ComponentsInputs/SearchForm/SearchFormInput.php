<?php

namespace App\View\Components\ComponentsInputs\SearchForm;

class SearchFormInput
{
    public string $inputName;
    public string $displayName;
    public ?string $inputId;
    public $inputValue;
    public string $errorField;
    public bool $required;

    public function __construct(
        string $inputName,
        string $displayName,
        string $errorField,
        bool $required,
        ?string $inputId = null,
        $inputValue = null
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