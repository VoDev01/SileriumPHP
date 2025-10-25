<?php

namespace App\Services\SearchForms\FormInputData;

/**
 * Hidden input field of the SearchForm
 */
class SearchFormHiddenInput
{

    /**
     * @param string $inputName Name attribute
     * @param string|null $inputId Id attribute
     * @param [type] $inputValue Value attribute
     */
    public function __construct(
        public string $inputName,
        public string $inputId,
        public $inputValue
    )
    {}
}
