<?php

namespace App\Http\Livewire\Admin;

use App\Enums\Files\FileType;
use App\Models\Account;
use App\Models\Activity;
use App\Models\Lead;
use App\Models\LOFile;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class ActivityComponent extends Component
{
    use WithFileUploads;

    /**
     * The activity form
     * @var string
     */
    public string       $activity    = '';
    public Lead         $lead;
    public Account      $account;
    public Order        $order;
    public bool         $guest       = false;
    public bool         $private     = false;
    public bool         $partnerMode = false;

    /**
     * Set Post States
     * @var bool
     */
    public bool $photoMode    = false;
    public bool $calendarMode = false;

    public        $photo;
    public string $date = '';
    public string $mode;

    public Collection $activities;

    /**
     * Get REFID Based on Mode
     * @return int
     */
    public function getRefId(): int
    {
        return match ($this->mode)
        {
            'LEAD' => $this->lead->id,
            'ACCOUNT' => $this->account->id,
            'ORDER' => $this->order->id,
            default => 0
        };
    }


    /**
     * Setup Activity Component
     * @return void
     */
    public function mount()
    {
        if (isset($this->lead))
        {
            $this->mode = 'LEAD';
        }
        elseif (isset($this->account))
        {
            $this->mode = 'ACCOUNT';
        }
        elseif (isset($this->order))
        {
            $this->mode = 'ORDER';
        }

        if (isset($this->lead) && $this->lead->partner)
        {
            $this->partnerMode = true;
        }

        $this->renderList();

    }

    /**
     * Render Activity Component for Lead
     * @return View
     */
    public function render(): View
    {
        if ($this->photoMode)
        {
            $this->emit('initDrop');
        }
        if ($this->calendarMode)
        {
            $this->emit('initDatePicker');
        }
        if (user()->account->id > 1)
        {
            return view('shop.account.activity');
        }
        else
        {
            return view('admin.widgets.activity');
        }
    }

    /**
     * Add activity
     * @return void
     */
    public function save()
    {
        $act = new Activity;
        $act->type = $this->mode;
        $act->refid = $this->getRefId();
        $act->private = $this->private;
        if (!$this->guest)
        {
            $act->user_id = user()->id;
        }
        else
        {
            $act->user_id = $this->order->account->admin->id;
        }
        if ($this->photoMode)
        {
            // Save Photo
            $act->image_id = $this->savePhoto();
        }
        if ($this->calendarMode)
        {
            $parsed = Carbon::parse($this->date);
            info("Parsed $this->date into " . $parsed->toDayDateTimeString());
            $act->event = $parsed;
        }
        $act->post = $this->activity;
        if ($act->post)
        {
            $act->save();
            if ($this->mode == 'LEAD')
            {
                $this->lead->touch();
            }
            if ($this->mode == 'ORDER')
            {
                $this->order->touch();
            }
        }

        // If this is a lead and has a partner then we should submit the note to the partner as well.
        $this->checkForLeadPartner();

        $this->activity = '';
        $this->photoMode = false;
        $this->calendarMode = false;
        $this->renderList();
    }


    /**
     * Render activity items.
     *
     * @return void
     */
    public function renderList(): void
    {
        if ($this->guest || user()->account->id > 1)
        {
            $this->activities = Activity::where('type', $this->mode)
                ->where('refid', $this->getRefId())
                ->where('system', 0)
                ->where('private', 0)
                ->orderBy('created_at', 'DESC')->take(20)->get();
        }
        else
        {
            $this->activities = Activity::where('type', $this->mode)
                ->where('refid', $this->getRefId())
                ->where('system', 0)
                ->orderBy('created_at', 'DESC')->take(20)->get();
        }
    }

    /**
     * Update Activity Feed
     * @return void
     */
    public function updates(): void
    {
        $this->renderList();
    }

    /**
     * Toggle Photo Mode
     * @return void
     */
    public function togglePhoto()
    {
        $this->calendarMode = false;
        $this->photoMode = !$this->photoMode;
    }

    /**
     * Enable/Disable Calendar Mode
     * @return void
     */
    public function toggleCalendar(): void
    {
        $this->photoMode = false;
        $this->photo = '';
        $this->calendarMode = !$this->calendarMode;
    }

    /**
     * Toggle the comment's privacy.
     * @return void
     */
    public function togglePrivate(): void
    {
        $this->private = !$this->private;
    }

    /**
     * Save photo in LO File
     * @return int
     */
    public function savePhoto(): int
    {
        $location = FileType::Image->location();
        $name = $this->photo->getClientOriginalName();
        $ext = explode(".", $name);
        if (!isset($ext[1])) $ext[1] = '.unk';               // Don't crash, call this an unknown file ext.
        $real = sprintf("%s.%s", uniqid("LO-"), $ext[1]);    /// 1235h123.pdf

        Storage::disk('local')->putFileAs($location, $this->photo, $real);
        $lo = (new LOFile)->create(
            [
                'hash'          => uniqid(),
                'filename'      => $name,
                'real'          => $real,
                'description'   => "Created " . now()->toDayDateTimeString(),
                'location'      => $location,
                'type'          => FileType::Image,
                'ref_id'        => $this->getRefId(),
                'mime_type'     => $this->photo->getMimeType(),
                'filesize'      => $this->photo->getSize(),
                'account_id'    => $this->mode == 'LEAD' || $this->guest ? 0 : $this->account->id,
                'auth_required' => 0
            ]
        );
        _log($lo, 'Photo has been uploaded');
        return $lo->id;
    }

    /**
     * If mode is lead and lead is set and partner is set. Transmit this to the partner.
     * @return void
     */
    private function checkForLeadPartner(): void
    {
        if ($this->mode != 'LEAD') return;
        if (!isset($this->lead)) return;
        if ($this->lead->partner && $this->activity)
        {
            try
            {
                $this->lead->partner->submitLeadActivity($this->lead, $this->activity);

            } catch (\Exception $e)
            {
                info("Could not send message to partner. " . $e->getMessage());
            }
        }
    }
}
