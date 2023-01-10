<?php

namespace App\Models;

use App\Enums\Core\LNPStatus;
use App\Operations\Core\MakePDF;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed $hash
 * @property mixed $provider
 * @property mixed $p_company
 * @property mixed $p_contact
 * @property mixed $p_provider
 * @property mixed $p_address
 * @property mixed $p_city
 * @property mixed $p_state
 * @property mixed $p_zip
 * @property mixed $p_btn
 * @property mixed $p_numbers
 * @property mixed $customer_sent_on
 * @property mixed $p_signature
 * @property mixed $signed_on
 * @property mixed $submitted_on
 * @property mixed $foc
 * @property mixed $ddd
 * @property mixed $completed_on
 * @property mixed $order
 * @property mixed $status
 * @property mixed $rejection_reason
 */
class LNPOrder extends Model
{
    public    $table   = 'lnp_orders';
    public    $dates   = ['submitted_on', 'ddd', 'foc', 'signed_on', 'customer_sent_on'];
    protected $guarded = ['id'];
    public    $casts   = ['status' => LNPStatus::class];

    /**
     * An LNP order belongs to a master order.
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the LOA Status
     * @return string
     */
    public function getLOAStatus(): string
    {
        return "N/A";
    }

    /**
     * An order is submitted to a provider.
     * @return BelongsTo
     */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    /**
     * Get Link Attribute for main order.
     * @return string
     */
    public function getLinkAttribute(): string
    {
        return $this->order->link;
    }

    /**
     * Get the LOA Link for Customer Signing
     * @return string
     */
    public function getLoaLinkAttribute(): string
    {
        return sprintf("%s/loa/%s", setting('brand.url'), $this->hash);
    }

    /**
     * Return if all fields have been entered that are required.
     * @return bool
     */
    public function hasValidFields(): bool
    {
        return $this->p_company && $this->p_contact && $this->p_provider && $this->p_address &&
            $this->p_city && $this->p_state && $this->p_zip && $this->p_btn && $this->p_numbers;
    }

    /**
     * Get the status tree for our submission list
     * @return array
     */
    public function getStatusTreeAttribute(): array
    {
        $items = [];
        // First lets validate our data.
        $items[] = (object)[
            'name'     => "Provider Selected",
            'complete' => (bool)$this->provider,
            'message'  => $this->provider ? $this->provider->name : "No provider selected"
        ];


        $items[] = (object)[
            'name'     => "Order Information",
            'complete' => $this->hasValidFields(),
            'message'  => $this->hasValidFields() ? "All fields completed." : "Missing Fields."
        ];

        $items[] = (object)[
            'name'     => "Desired Due Date",
            'complete' => (bool)$this->ddd,
            'message'  => $this->ddd ? "Date Set" : "Soonest Available",
            'date'     => $this->ddd
        ];


        $items[] = (object)[
            'name'     => "LOA Sent to Customer",
            'complete' => (bool)$this->customer_sent_on,
            'message'  => $this->customer_sent_on ? "Sent" : "Not Sent",
            'date'     => $this->customer_sent_on
        ];

        $items[] = (object)[
            'name'     => "LOA Signed by Customer",
            'complete' => (bool)$this->p_signature,
            'message'  => $this->p_signature ? "Signed" : "Not Signed",
            'date'     => $this->signed_on
        ];

        $items[] = (object)[
            'name'     => "Port Submitted to Provider",
            'complete' => (bool)$this->submitted_on,
            'message'  => $this->submitted_on ? "Submitted" : "Unsubmitted",
            'date'     => $this->submitted_on
        ];

        $items[] = (object)[
            'name'     => "FOC Received",
            'complete' => (bool)$this->foc,
            'message'  => $this->foc ? "Received" : "Not Found",
            'date'     => $this->foc
        ];

        $items[] = (object)[
            'name'     => "Number(s) Transferred",
            'complete' => (bool)$this->completed_on,
            'message'  => $this->completed_on ? "Completed" : "Incomplete",
            'date'     => $this->completed_on
        ];
        return $items;
    }

    /**
     * Generate LOA PDF
     * @param bool $save
     * @return mixed
     */
    public function pdf(bool $save = false): mixed
    {
        $pdf = new MakePDF();
        $pdf->setName("LNP-$this->id.pdf");
        $data = view("pdf.loa")->with('lnp', $this)->render();
        if (!$save)
        {
            return $pdf->streamFromData($data);
        }
        else return storage_path() . "/" . $pdf->saveFromData($data);
    }

    /**
     * Send LOA Request to Customer
     * @return void
     */
    public function sendToCustomer(): void
    {
        template('account.loa', $this->order->account->admin, [$this]);
        $this->update(['status' => LNPStatus::PendingSignature, 'customer_sent_on' => now()]);
    }

    /**
     * Get Raw Attribute for Provider
     * @return string

    public function getRawAttribute(): string
    {
        return view('voip::admin.lnp_orders.raw')->with('lnp', $this)->render();
    }
     */

    /**
     * Submit order to Provider
     * @return void
     */
    public function submitToProvider()
    {
        template('account.lnporder', null, [$this], [$this->pdf(true)],
            $this->provider->lnp_email, $this->provider->name);
        $this->update(['status' => LNPStatus::Submitted, 'submitted_on' => now()]);
    }

    /**
     * Return an array of the numbers to be transferred.
     * @return array
     */
    public function getInventoryAttribute(): array
    {
        $nums = [];
        foreach (explode("\n", $this->p_numbers) as $num)
        {
            $nums[] = makeTn(preg_replace("/\D+/", '', $num));
        }
        return $nums;
    }

    /**
     * Get active options
     * @return string[]
     */
    public function getActiveOptions(): array
    {
        return [
            LNPStatus::Submitted->value => 'Submitted',
            LNPStatus::Rejected->value  => 'Rejected',
            LNPStatus::FOC->value       => 'FOC',
            LNPStatus::Completed->value => 'Completed'
        ];
    }

    /**
     * Send a copy of the signed LOA to the customer
     * @return void
     */
    public function sendSignedToCustomer()
    {
        template('account.loasigned', $this->order->account->admin, [$this], [$this->pdf(true)]);
    }

    /**
     * This is used to get a human/customer readable update to the order.
     * Used in email templates.
     * @return string
     */
    public function getUpdateHumanAttribute(): string
    {
        $data = "Order Status: <b>{$this->status->value}</b>

        ";
        if ($this->status == LNPStatus::Rejected)
        {
            $data .= "Your order has been rejected due to the following reason:
            <pre>
            $this->rejection_reason
            </pre>
            Please reply to this notification with the information required. If you believe all the information is correct, you may have to contact the current provider for a CSR (Customer Service Record) as their information may be outdated.
            ";
        }
        if ($this->status == LNPStatus::FOC)
        {
            $data .= "Great News! Your order has received a commitment date from the losing carrier.
            They have agreed to release the number on <b>".$this->foc->format("m/d")."</b>, at which time it will be transferred. Once your numbers have been successfully ported, you will receive a final notification informing you that you can cancel your existing service.";
        }

        if ($this->status == LNPStatus::Completed)
        {
            $data .= "Order Complete! Your numbers have been successfully transferred, and you can cancel service with your previous provider.

            <b>NOTE!</b> Most losing providers are not notified when a port completes so you should call and confirm that your services have been terminated.";
        }

        if ($this->status == LNPStatus::Submitted)
        {
            $data .= "Your order has been submitted (or updated) with information you have provided. We are currently waiting from a response from the losing carrier on a commitment date.";
        }
        return $data;
    }

    /**
     * Send customer an update on their order.
     * @return void
     */
    public function sendCustomerUpdate(): void
    {
        template('account.lnpupdate', $this->order->account->admin, [$this]);
    }

}
