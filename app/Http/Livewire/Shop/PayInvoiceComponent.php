<?php

namespace App\Http\Livewire\Shop;

use App\Enums\Core\PaymentMethod;
use App\Exceptions\LogicException;
use App\Models\Invoice;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class PayInvoiceComponent extends Component
{
    public Invoice $invoice;

    public string $errorMessage = '';

    /**
     * Render Button
     * @return View
     */
    public function render(): View
    {
        return view('shop.account.invoices.payComponent');
    }


    /**
     * Authorize Transaction and Redirect back
     * @return void
     */
    public function authorize(): void
    {
        $this->invoice->refresh();
        if ($this->invoice->balance > 0)
        {
            try
            {
                $method = PaymentMethod::from('Credit Card');
                $this->invoice->processPayment($method, $this->invoice->balance, "Authorized from Customer (via Shop)");
                $this->invoice->refresh();
                $this->errorMessage = '';
            } catch (LogicException $e)
            {
                $this->errorMessage = "Unable to process payment: " . $e->getMessage();
            }
        }
    }
}
