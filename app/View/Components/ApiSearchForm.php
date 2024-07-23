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
    public function __construct(string $actionUrl, string $header, string $loadWith, string $redirect)
    {
        $this->actionUrl = $actionUrl;
        $this->header = $header;
        $this->loadWith = $loadWith;
        $this->redirect = $redirect;
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
