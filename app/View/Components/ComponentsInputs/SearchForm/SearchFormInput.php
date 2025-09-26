<?php

namespace App\View\Components\ComponentsInputs\SearchForm;

/**
 * Text input of the SearchForm
 */
class SearchFormInput
{
    public string $inputName;
    public string $displayName;
    public ?string $inputId;
    public $inputValue;
    public string $errorField;
    public bool $required;

    /**
     * @param string $inputName Name attribute
     * @param string $displayName Lable text
     * @param string $errorField Error field name (same as name attribute)
     * @param boolean $required Should the field be required
     * @param string|null $inputId Id attribute
     * @param [type] $inputValue Value attribute
     */
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