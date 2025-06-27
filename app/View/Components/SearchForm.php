<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SearchForm extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $inputs;
    public $queryInputs;
    public $hiddenInputs;
    public $checkboxInputs;
    public string $header;
    public string $submitId;
    public function __construct(
        $inputs,
        $queryInputs,
        $hiddenInputs = null,
        $checkboxInputs = null, 
        string $header = 'Поиск',
        string $submitId = ''
    )
    {
        $this->queryInputs = $queryInputs;
        $this->hiddenInputs = $hiddenInputs;
        $this->checkboxInputs = $checkboxInputs;
        $this->inputs = $inputs;
        $this->header = $header;
        $this->submitId = $submitId;
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
