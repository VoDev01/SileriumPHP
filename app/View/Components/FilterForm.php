<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FilterForm extends Component
{
    public string $filterActionLink;
    public string $filterActionParams;
    public string $filterRubCurrencyLink;
    public string $filterDolCurrencyLink;
    public int $sortOrder;
    public bool $popularity;
    public bool $price;
    public bool $currency;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $filterActionLink, string $filterActionParams, string $filterRubCurrencyLink, string $filterDolCurrencyLink, int $sortOrder, 
    bool $popularity = false, bool $price = false, bool $currency = false)
    {
        $this->filterActionLink = $filterActionLink;
        $this->filterActionParams = $filterActionParams;
        $this->filterRubCurrencyLink = $filterRubCurrencyLink;
        $this->filterDolCurrencyLink = $filterDolCurrencyLink;
        $this->sortOrder = $sortOrder;
        $this->popularity = $popularity;
        $this->price = $price;
        $this->currency = $currency;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.filter-form');
    }
}
