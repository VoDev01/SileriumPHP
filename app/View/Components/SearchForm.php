<?php

namespace App\View\Components;

use ComponentsInputs\SearchForm\SearchFormCheckboxInputs;
use ComponentsInputs\SearchForm\SearchFormHiddenInputs;
use Illuminate\View\Component;
use ComponentsInputs\SearchForm\SearchFormInputs;
use ComponentsInputs\SearchForm\SearchFormQueryInputs;

class SearchForm extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public array $inputs;
    public SearchFormQueryInputs $queryInputs;
    public array $hiddenInputs;
    public array $checkboxInputs;
    public string $header;
    public string $submit_id;
    public function __construct(
        array $inputs,
        SearchFormQueryInputs $queryInputs,
        array $hiddenInputs = null,
        array $checkboxInputs = null, 
        string $header = 'Поиск',
        string $submit_id = ''
    )
    {
        $this->$queryInputs = $queryInputs;
        $this->hiddenInputs = $hiddenInputs;
        $this->checkboxInputs = $checkboxInputs;
        $this->inputs = $inputs;
        $this->$header = $header;
        $this->submit_id = $submit_id;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.search-form');
    }
}
