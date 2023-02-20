<?php

namespace App\Operations\Admin;

use App\Enums\Core\InvoiceStatus;
use App\Models\Account;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Quote;
use App\Models\Transaction;

/**
 * Generates Widget Data based on the type of widget requested.
 */
class WidgetGenerator
{

    /**
     * Handler for widget
     * @param string $name
     * @return object
     */
    static public function get(string $name): object
    {
        $x = new self();
        return $x->$name();
    }

    /**
     * Get invoices invoiced today vs yesterday
     * @return object
     */
    public function invoicedToday(): object
    {
        $total = 0;
        $invoices = Invoice::with(['items', 'transactions'])->where('status', '!=', InvoiceStatus::DRAFT->value)
            ->whereDate('created_at', now()->format("Y-m-d"))->get();
        foreach ($invoices as $invoice)
        {
            $total += $invoice->total;
        }
        // Difference from the day before.
        $pasts = Invoice::with(['items', 'transactions'])->where('status', '!=', InvoiceStatus::DRAFT->value)
            ->whereDate('created_at', now()->subDay()->format("Y-m-d"))->get();
        $pastTotal = 0;
        foreach ($pasts as $past)
        {
            $pastTotal += $past->total;
        }

        $perc = $this->getPerc($total, $pastTotal);
        return (object)[
            'total' => $total,
            'color' => $perc > 0 ? 'success' : 'danger',
            'name'  => "Invoiced Today",
            'perc'  => $perc,
            'icon'  => '7176657'
        ];
    }

    /**
     * Show outstanding balance
     * @return object
     */
    public function outstandingInvoices(): object
    {
        $invoices = Invoice::with(['items', 'transactions'])->whereIn('status', [InvoiceStatus::SENT->value, InvoiceStatus::PARTIAL->value])->get();
        $total = 0;
        foreach ($invoices as $invoice)
        {
            $total += $invoice->balance;
        }
        return (object)[
            'name'  => "Outstanding Invoice Balance",
            'icon'  => '7595981',
            'total' => $total
        ];
    }

    /**
     * Get daily transactions
     * @return object
     */
    public function getTransactions(): object
    {
        $total = 0;
        $invoices = Transaction::select('amount')->whereDate('created_at', now()->format("Y-m-d"))->get();
        foreach ($invoices as $invoice)
        {
            $total += $invoice->amount;
        }
        // Difference from the day before.
        $pasts = Transaction::select('amount')->whereDate('created_at', now()->subDay()->format("Y-m-d"))->get();
        $pastTotal = 0;
        foreach ($pasts as $past)
        {
            $pastTotal += $past->amount;
        }

        $perc = $this->getPerc($total, $pastTotal);
        return (object)[
            'total' => $total,
            'color' => $perc > 0 ? 'success' : 'danger',
            'name'  => "Transactions Processed",
            'perc'  => $perc,
            'icon'  => '3186949'
        ];
    }


    /**
     * Get monthly MRR
     * @return object
     */
    public function getMRR(): object
    {
        $total = 0;
        $grand = 0;
        foreach (Account::with(['items', 'items.addons'])->where('active', true)->get() as $account)
        {
            $grand += $account->mrr;
        }
        $accounts = Account::with(['items', 'items.addons'])->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->get();
        foreach ($accounts as $account)
        {
            $total += $account->mrr;
        }
        // Difference from the day before.
        $pasts = Account::with(['items', 'items.addons'])->whereBetween('created_at',
            [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->get();
        $pastTotal = 0;
        foreach ($pasts as $past)
        {
            $pastTotal += $past->mrr;
        }

        $perc = $this->getPerc($total, $pastTotal);
        return (object)[
            'total' => $grand,
            'color' => $perc > 0 ? 'success' : 'danger',
            'name'  => "Monthly MRR",
            'perc'  => $perc,
            'icon'  => '1728946'
        ];
    }

    /**
     * Get Leads
     * @return object
     */
    public function getLeads(): object
    {
        $total = Lead::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();

        // Difference from the day before.
        $pasts = Lead::whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->count();


        $perc = $this->getPerc($total, $pasts);
        return (object)[
            'total'     => $total,
            'color'     => $perc > 0 ? 'success' : 'danger',
            'name'      => "Leads for Month",
            'perc'      => $perc,
            'icon'      => '2275248',
            'direction' => $perc > 0 ? 'up' : 'down'
        ];
    }

    /**
     * Get monthly MRR
     * @return object
     */
    public function quotedAmount(): object
    {
        $total = 0;
        $quotes = Quote::with(['items', 'services', 'products', 'items.addons', 'services.addons', 'products.addons'])->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->get();
        foreach ($quotes as $quote)
        {
            $total += $quote->total;
        }
        // Difference from the day before.
        $pasts = Quote::with(['items', 'services', 'products', 'items.addons', 'services.addons', 'products.addons'])->whereBetween('created_at',
            [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->get();
        $pastTotal = 0;
        foreach ($pasts as $past)
        {
            $pastTotal += $past->total;
        }

        $perc = $this->getPerc($total, $pastTotal);
        return (object)[
            'total' => $total,
            'color' => $perc > 0 ? 'success' : 'danger',
            'name'  => "Quoted for Month",
            'perc'  => $perc,
            'icon'  => '1358656',
            'direction' => $perc > 0 ? 'up' : 'down'

        ];
    }

    /**
     * Get quote forecasted
     * @return object
     */
    public function getForecasted(): object
    {
        $total = 0;
        $leads = Lead::with('quotes')->whereBetween('forecast_date', [now()->startOfMonth(), now()->endOfMonth()])->get();
        foreach ($leads as $lead)
        {
            foreach ($lead->quotes as $quote)
            {
                $total += $quote->total;
            }
        }
        // Difference from the day before.
        $pastTotal = 0;
        $pasts = Lead::with('quotes')->whereBetween('forecast_date', [now()->startOfMonth(), now()->endOfMonth()])->get();
        foreach ($pasts as $lead)
        {
            foreach ($lead->quotes as $quote)
            {
                $pastTotal += $quote->total;
            }
        }

        $perc = $this->getPerc($total, $pastTotal);
        return (object)[
            'total' => $total,
            'color' => $perc > 0 ? 'success' : 'danger',
            'name'  => "Forecasted for Month",
            'perc'  => $perc,
            'icon'  => '4395998',
            'direction' => $perc > 0 ? 'up' : 'down'
        ];
    }

    /**
     * Get ecommerce quote amount
     * @return object
     */
    public function getEcommerceQuotes(): object
    {
        $total = 0;
        $leads = Lead::with('quotes')->where('guest_created', true)->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->get();
        foreach ($leads as $lead)
        {
            foreach ($lead->quotes as $quote)
            {
                $total += $quote->total;
            }
        }
        // Difference from the day before.
        $pastTotal = 0;
        $pasts = Lead::with('quotes')->where('guest_created', true)->whereBetween('forecast_date', [now()->startOfMonth(), now()->endOfMonth()])->get();
        foreach ($pasts as $lead)
        {
            foreach ($lead->quotes as $quote)
            {
                $pastTotal += $quote->total;
            }
        }

        $perc = $this->getPerc($total, $pastTotal);
        return (object)[
            'total' => $total,
            'color' => $perc > 0 ? 'success' : 'danger',
            'name'  => "Ecommerce Self-Quoted For Month",
            'perc'  => $perc,
            'icon'  => '1162499',
            'direction' => $perc > 0 ? 'up' : 'down'
        ];
    }

    /**
     * Get Percentage from Today VS Yesterday
     * @param float $today
     * @param float $yesterday
     * @return int
     */
    private function getPerc(float $today, float $yesterday): int
    {
        if ($yesterday == 0 && $today > 0)
        {
            $perc = 100;
        }
        elseif ($yesterday > 0 && $today == 0) $perc = -100;
        elseif ($yesterday == 0 && $today == 0) $perc = 0;
        else
        {
            $diff = $today - $yesterday; // 100 - 50 (bal
            $perc = round($diff / $yesterday * 100);
        }
        return $perc;
    }

}
