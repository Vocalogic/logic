<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FormInput extends Component
{

    public string $name;
    public string $label;
    public ?string $value;
    public ?int $labelWidth = 4; // Assuming Field is 8.
    public ?string $icon;
    public int $fieldWidth  = 8;
    public bool $float      = false;
    public string $type;
    public ?string $placeholder;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $label,
        string $name,
        ?string $value = null,
        ?string $icon = null,
        bool $float = false,
        ?int $labelWidth = 4,
        ?string $type = 'text',
        ?string $placeholder = null
    ) {
        $this->label = $label;
        $this->name = $name;
        $this->value = $value;
        $this->icon = $icon;
        $this->float = $float;
        $this->labelWidth = $labelWidth;
        $this->fieldWidth = 12 - $this->labelWidth;
        $this->type = $type;
        $this->placeholder = $placeholder;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View
     */
    public function render(): View
    {
        return view('components.form-input');
    }
}
