<?php

namespace App\Services\SearchForms\FormInputData;

/**
 * Text input of the SearchForm
 */
class SearchFormInput
{

    /**
     * @param string $inputName Name attribute
     * @param string $displayName Lable text
     * @param string $errorField Error field name (same as name attribute)
     * @param boolean $required Should the field be required
     * @param string|null $inputId Id attribute
     * @param [type] $inputValue Value attribute
     */
    public function __construct(
        public string $inputName,
        public string $displayName,
        public string $errorField,
        public bool $required,
        public ?string $inputId = null,
        public $inputValue = null
    )
    {}
}