<?php

namespace App\Http\Livewire\Admin;

use App\Models\Activity;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class DashboardActivityComponent extends Component
{
    public Collection $activities;

    public function mount()
    {
        $this->activities = Activity::orderBy('created_at', 'DESC')->take(10)->get();
    }

    /**
     * Reload activity
     * @return void
     */
    public function loadActivity()
    {
        $this->activities = Activity::orderBy('created_at', 'DESC')->take(10)->get();
    }

    public function render():View
    {
        return view('admin.dashboard.feed');
    }

}
