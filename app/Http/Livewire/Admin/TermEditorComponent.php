<?php

namespace App\Http\Livewire\Admin;

use App\Models\Term;
use Illuminate\Contracts\View\View;
use League\CommonMark\CommonMarkConverter;
use Livewire\Component;

class TermEditorComponent extends Component
{
    public Term $term;

    public string $data = '';
    public string $converted;

    protected CommonMarkConverter $converter;

    /**
     * Setup Editor
     * @return void
     */
    public function mount(): void
    {
        $this->data = $this->term->body ? :'';
    }

    /**
     * Set converted
     * @return void
     */
    public function convert() : void
    {
        $this->converter = new CommonMarkConverter([
            'html_input'         => 'strip',
            'allow_unsafe_links' => false
        ]);

        $this->converted = $this->converter->convert($this->data);
    }

    public function render(): View
    {
        $this->convert();
        return view('admin.terms.component');
    }

    public function save() : void
    {
        $this->term->update(['body' => $this->data]);

    }

}
