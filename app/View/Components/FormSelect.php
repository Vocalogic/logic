<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormSelect extends Component
{
    public string $name;
    public string $label;
    public ?string $selected;
    public array $options;
    public bool $float = false;
    public ?string $icon;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $name, string $label, ?string $selected = null, ?array $options = [], ?bool $float = false, ?string $icon = null)
    {
        $this->name = $name;
        $this->label = $label;
        $this->selected = $selected;
        $this->options = $options;
        $this->float = $float;
        $this->icon = $icon;

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form-select');
    }
}
