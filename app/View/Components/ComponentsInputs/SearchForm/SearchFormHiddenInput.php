<?php

namespace App\View\Components\ComponentsInputs\SearchForm;

/**
 * Hidden input field of the SearchForm
 */
class SearchFormHiddenInput
{
    public string $inputName;
    public string $inputId;
    public $inputValue;

    /**
     * @param string $inputName Name attribute
     * @param string|null $inputId Id attribute
     * @param [type] $inputValue Value attribute
     */
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
