<?php

namespace App\Http\Livewire\Admin;

use App\Models\Lead;
use Illuminate\View\View;
use Livewire\Component;

class PartnerLeadComponent extends Component
{
    public Lead $lead;
    public array $data = [];
    public string $loadingMessage = "Asking for Lead Updates from Partner..";
    public string $errorMessage = '';

    /**
     * Contact Our Partner Host and get information regarding this lead
     * @return void
     */
    public function mount(): void
    {
        $this->getLeadUpdates();
    }

    /**
     * Render the partner component for a lead.
     * @return View
     */
    public function render(): View
    {
        return view('admin.leads.partner.component');
    }

    /**
     * Get Lead Updates from Host
     * @return void
     */
    private function getLeadUpdates() : void
    {
        try
        {
            $this->data = (array)$this->lead->partner->getLeadUpdate($this->lead);
        } catch (\Exception $e)
        {
            $this->loadingMessage = '';
            $this->errorMessage = $e->getMessage();
            return;
        }
        $this->loadingMessage = '';
    }

}
