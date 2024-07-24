<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ApiSearchForm extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public string $actionUrl;
    public string $header;
    public string $loadWith;
    public string $redirect;
    public array $inputs;
    public string $submit_id;
    public bool $required;
    public function __construct(string $actionUrl, string $header, string $loadWith, string $redirect, array $inputs, string $submit_id = '', bool $required = false)
    {
        $this->actionUrl = $actionUrl;
        $this->header = $header;
        $this->loadWith = $loadWith;
        $this->redirect = $redirect;
        $this->inputs = $inputs;
        $this->submit_id = $submit_id;
        $this->required = $required;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.api-search-form');
    }
}
