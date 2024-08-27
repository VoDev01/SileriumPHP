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
    public string $searchActionUrl;
    public string $header;
    public string $loadWith;
    public string $redirect;
    public array $inputs;
    public array $checkboxInputs;
    public array $hiddenInputs;
    public string $submit_id;
    public function __construct(string $searchActionUrl, string $header = 'Поиск', string $loadWith = '', string $redirect = '#', 
    array $hiddenInputs = [], array $inputs = [], array $checkboxInputs = [], string $submit_id = '')
    {
        $this->searchActionUrl = $searchActionUrl;
        $this->header = $header;
        $this->loadWith = $loadWith;
        $this->redirect = $redirect;
        $this->hiddenInputs = $hiddenInputs;
        $this->checkboxInputs = $checkboxInputs;
        $this->inputs = $inputs;
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
