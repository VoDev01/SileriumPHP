<?php


namespace App\View\Components\ComponentsInputs\SearchForm;

class SearchFormCheckboxInput extends SearchFormInput
{
    public bool $checked;
    public function __construct(
        string $inputName,
        string $displayName,
        string $errorField,
        bool $required,
        bool $checked = false,
        ?string $inputId = null,
        $inputValue = null
    )
    {
        parent::__construct($inputName, $displayName, $errorField, $required, $inputId, $inputValue);
        $this->checked = $checked;
    }
}
