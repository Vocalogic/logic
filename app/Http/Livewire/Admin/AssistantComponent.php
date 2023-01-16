<?php

namespace App\Http\Livewire\Admin;

use Illuminate\Support\Collection;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\Redirector;

class AssistantComponent extends Component
{
    public string $cid;
    public Collection $cart;

    /**
     *
     * @return void
     */
    public function mount() : void
    {
        $this->cart = collect(sbus()->get($this->cid));
    }

    /**
     * Update View
     * @return Redirector|null
     */
    public function live(): Redirector|null
    {
        $this->cart = collect(sbus()->get($this->cid));

        if (!$this->cart->get('id'))
        {
            return redirect()->to("/");
        }
        $this->checkForClears();
        return null;
    }

    public function render(): View
    {
        return view('admin.assistant.component');
    }

    /**
     * Iterate our carts and find anything that has an executed = true
     * and if it does, clear the stack.
     * @return void
     */
    private function checkForClears() : void
    {
        if ($this->cart->get('executed'))
        {
            sbus()->clear($this->cart->get('id'));
        }
    }


}
