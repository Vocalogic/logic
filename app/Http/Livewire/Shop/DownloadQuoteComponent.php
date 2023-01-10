<?php

namespace App\Http\Livewire\Shop;

use App\Enums\Core\CommKey;
use App\Models\BillItem;
use App\Models\Lead;
use App\Models\Quote;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Livewire\Component;

class DownloadQuoteComponent extends Component
{
    public $listeners = ['verificationComplete'];

    public bool $enabled = false;

    public string $company      = '';
    public string $contact      = '';
    public string $email        = '';
    public string $errorMessage = '';
    public bool   $thanks       = false; // Don't allow spamming. Remove this form when complete.

    /**
     * Download Quote Component
     * @return View
     */
    public function render(): View
    {
        return view('shop.download');
    }

    /**
     * Listen for verification process to complete.
     * @return void
     */
    public function verificationComplete(): void
    {
        $this->enabled = true;
    }

    public function send(): void
    {
        $this->errorMessage = '';
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL))
        {
            $this->errorMessage = 'Invalid email address entered';
            return;
        }
        if (!$this->company)
        {
            $this->errorMessage = 'Please enter a company name for the quote';
            return;
        }
        if (!$this->contact)
        {
            $this->errorMessage = 'Please enter a contact name for the quote.';
            return;
        }

        $cart = cart();
        $items = $cart->items;
        if (empty($items))
        {
            $this->errorMessage = 'Your cart is empty. Unable to send quote.';
            return;
        }

        // Ok lets create a lead.
        $lead = (new Lead)->create([
            'company'        => $this->company,
            'contact'        => $this->contact,
            'email'          => $this->email,
            'active'         => 1,
            'hash'           => uniqid('D-'),
            'lead_status_id' => 1,
            'lead_type_id'   => 1,
            'agent_id'       => 1,
            'guest_created'  => true
        ]);

        $lead->refresh();
        // Now we need to create a quote based on msrp or base.
        $quote = (new Quote)->create([
            'name'           => "Quote for $this->company",
            'lead_id'        => $lead->id,
            'hash'           => "QO-" . uniqid(),
            'preferred'      => true,
            'net_terms'      => 0,
            'expires_on'     => now()->addDays((int)setting('quotes.length')),
            'lead_status_id' => 1,
            'archived'       => 0,
            'presentable'    => true,

        ]);
        if (session(CommKey::LocalPackageAnswerSession->value))
        {
            $quote->update(['package_answers' => session(CommKey::LocalPackageAnswerSession->value)]);
            Session::forget(CommKey::LocalPackageAnswerSession->value);
        }
        $quote->refresh();
        $msrp = setting('quotes.msrp') == 'Yes';

        foreach ($items as $item)
        {
            if ($msrp)
            {
                $price = $item->price;
            }

            else
            {
                $item = BillItem::find($item->id);
                $price = $item->type == 'products' ? $item->nrc : $item->mrc;
            }
            $quote->items()->create([
                'item_id'     => $item->id,
                'price'       => $price,
                'qty'         => $item->qty ?: 1,
                'description' => $item->description
            ]);

        }
        $quote->refresh();
        $quote->send();
        $this->thanks = true;
    }
}
