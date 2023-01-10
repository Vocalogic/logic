<?php

namespace App\Models;

use App\Enums\Core\ShipmentStatus;
use App\Operations\Core\MakePDF;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed $id
 * @property mixed $vendor
 * @property mixed $items
 * @property mixed $ship_company
 * @property mixed $ship_contact
 * @property mixed $ship_address
 * @property mixed $ship_address2
 * @property mixed $ship_csz
 * @property mixed $expected_arrival
 * @property mixed $order
 * @property mixed $tracking
 * @property mixed $created_at
 */
class Shipment extends Model
{
    protected $guarded = ['id'];

    public $casts = [
        'status' => ShipmentStatus::class,
    ];

    public $dates = [
        'shipped_on',
        'submitted_on',
        'expected_arrival'
    ];

    /**
     * A shipment belongs to an order.
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * A shipment has many items
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'shipment_id');
    }

    /**
     * A shipment belongs to a vendor
     * @return BelongsTo
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Return a streamed PDF.
     * @param bool $save
     * @return mixed
     * @throws NexusException
     */
    public function pdf(bool $save = false): mixed
    {
        $pdf = new MakePDF();
        $pdf->setName("Order-$this->id.pdf");
        $data = view("pdf.order")->with('order', $this)->render();
        if (!$save)
        {
            return $pdf->streamFromData($data);
        }
        else return storage_path() . "/" . $pdf->saveFromData($data);
    }

    /**
     * A text-summary of the order. This is generally used for
     * email templates for a vendor.
     * @return string
     */
    public function getVendorDetailAttribute(): string
    {
        $data = "

        SHIP TO:
        $this->ship_company
        C/O: $this->ship_contact
        $this->ship_address $this->ship_address2
        $this->ship_csz

        ORDER DETAILS:

        ";
        foreach ($this->items as $item)
        {
            $notes = $item->notes ? " ($item->notes)" : null;
            $data .= "$item->qty x {$item->item->name} $notes
            ";
        }
        return $data;
    }


    /**
     * Send the order to a vendor
     * @return void
     */
    public function sendToVendor(): void
    {
        template('account.order', null, [$this], [$this->pdf(true)], $this->vendor->rep_email, $this->vendor->rep_name);
    }

    /**
     * Send Tracking information to Customer.
     * @return void
     */
    public function sendTracking(): void
    {
        template('account.tracking', $this->order->account->admin, [$this], [$this->pdf(true)]);
    }


}
