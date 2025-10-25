<?php


namespace App\Services\SearchForms\FormInputData;

/**
 * Checkbox input field of the SearchForm
 */
class SearchFormCheckboxInput extends SearchFormInput
{
    public bool $checked = false;

    /**
     * @param string $inputName Name attribute
     * @param string $displayName Lable text
     * @param string $errorField Error field name (same as name attribute)
     * @param boolean $required Should the field be required
     * @param boolean $checked Should the field be checked
     * @param string|null $inputId Id attribute
     * @param [type] $inputValue Value attribute
     */
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
