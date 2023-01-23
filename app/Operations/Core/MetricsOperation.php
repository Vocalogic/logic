<?php

namespace App\Operations\Core;

use App\Enums\Core\CommissionStatus;
use App\Enums\Core\InvoiceStatus;
use App\Enums\Core\LeadStatus;
use App\Enums\Core\MetricType;
use App\Models\Account;
use App\Models\AccountItem;
use App\Models\Activity;
use App\Models\BillItem;
use App\Models\Commission;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Quote;
use App\Operations\API\NS\CDR;
use App\Operations\API\NS\Domain;
use App\Operations\API\NS\NSUser;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Exception\GuzzleException;

/**
 * This class will handle all metrics gathering
 */
class MetricsOperation
{
    static public function run(): void
    {
        $obj = new self;
        foreach (MetricType::cases() as $case)
        {
            if ($case->getCollector())
            {
                try
                {
                    $fn = $case->getCollector();
                    $obj->$fn();
                } catch (Exception $e)
                {
                    info("Unable to collect metric $case->value - " . $e->getMessage());
                }
            }
        }
    }

    /**
     * Create a metric with the total number of active leads
     * @return void
     */
    public function totalLeads(): void
    {
        $value = Lead::where('active', true)->count();
        _imetric(MetricType::TotalLeads, $value);
    }

    /**
     * Total number of quotes active during today.
     * @return void
     */
    public function totalQuoted(): void
    {
        $value = Quote::where('archived', false)->where('preferred', true)->count();
        _imetric(MetricType::TotalQuoted, $value);
    }

    /**
     * Total Quoted Value
     * @return void
     */
    public function totalQuotedValue(): void
    {
        $quotes = Quote::where('archived', false)->where('preferred', true)->get();
        $total = 0;
        foreach ($quotes as $quote)
        {
            $total += $quote->analysis->profit;
        }
        _imetric(MetricType::TotalQuotedValue, $total);
    }

    /**
     * Get total MRR for the quote.
     * @return void
     */
    public function totalQuotedMrr(): void
    {
        $quotes = Quote::where('archived', false)->where('preferred', true)->get();
        $total = 0;
        foreach ($quotes as $quote)
        {
            $total += $quote->mrr;
        }
        _imetric(MetricType::TotalQuoteMRR, $total);
    }

    /**
     * Get total Non Recurring Costs
     * @return void
     */
    public function totalQuotedNrc(): void
    {
        $quotes = Quote::where('archived', false)->where('preferred', true)->get();
        $total = 0;
        foreach ($quotes as $quote)
        {
            $total += $quote->nrc;
        }
        _imetric(MetricType::TotalQuoteNRC, $total);
    }

    /**
     * How many leads lost?
     * @return void
     */
    public function totalLost(): void
    {
        $count = Lead::whereHas('status', function($t) {
            $t->where('is_lost', true);
        })->count();
        _imetric(MetricType::TotalLost, $count);
    }

    /**
     * Number of leads updated
     * @return void
     */
    public function leadsTouched(): void
    {
        $date = now();
        $count = Lead::where('updated_at', '>=', $date->startOfDay())->count();
        _imetric(MetricType::LeadsTouched, $count);
    }

    /**
     * Number of activity items.
     * @return void
     */
    public function leadActivity(): void
    {
        $date = now();
        $count = Activity::where('created_at', '>=', $date->startOfDay())->count();
        _imetric(MetricType::LeadActivity, $count);
    }

    /**
     * Total MRR Company Wide
     * @return void
     */
    public function totalMrr(): void
    {
        $total = 0;
        foreach (AccountItem::all() as $item)
        {
            $total += ($item->price * $item->qty);
        }
        _imetric(MetricType::MRR, $total);
    }

    /**
     * Get per Account MRR
     * @return void
     */
    public function accountMrr(): void
    {
        foreach (Account::where('active', true)->get() as $account)
        {
            _imetric(MetricType::AccountMRR, $account->mrr, $account);
        }
    }

    /**
     * Get outstanding invoice balance amount.
     * @return void
     */
    public function accountInvoiced(): void
    {
        foreach (Account::where('active', true)->get() as $account)
        {
            $total = 0;
            foreach ($account->invoices()
                         ->where('status', '!=', InvoiceStatus::PAID)
                         ->where('status', '!=', InvoiceStatus::DRAFT)->get() as $invoice)
            {
                $total += $invoice->balance;
            }
            _imetric(MetricType::AccountInvoiced, $total, $account);
        }
    }

    public function accountTotalInvoiced(): void
    {
        foreach (Account::where('active', true)->get() as $account)
        {
            $total = 0;
            foreach ($account->invoices()
                         ->where('status', '!=', InvoiceStatus::DRAFT)->get() as $invoice)
            {
                $total += $invoice->total;
            }
            _imetric(MetricType::AccountTotalInvoiced, $total, $account);
        }
    }

    /**
     * Total Amount Outstanding on Invoices
     * @return void
     */
    public function totalOutstanding(): void
    {
        $invoices = Invoice::whereIn('status', [InvoiceStatus::PARTIAL->value, InvoiceStatus::SENT->value])
            ->get();
        $total = 0;
        foreach ($invoices as $invoice)
        {
            $total += $invoice->balance;
        }
        _imetric(MetricType::TotalOutstanding, $total);
    }

    /**
     * Get total MRR from Quotes that has a forecast date assigned
     * to the lead.
     * @return void
     */
    public function totalForecasted(): void
    {
        $quotes = Quote::where('archived', false)->where('preferred', true)->get();
        $total = 0;
        foreach ($quotes as $quote)
        {
            if ($quote->lead && $quote->lead->forecast_date)
            {
                $total += $quote->mrr;
            }
        }
        _imetric(MetricType::TotalForecasted, $total);
    }

    /**
     * Get amount forecasted for this month so far and 3 months in advance.
     * @return void
     */
    public function totalMonthForecast(): void
    {
        $quotes = Quote::where('archived', false)->where('preferred', true)->get();
        foreach (range(0, 3) as $i)
        {
            $total = 0;
            $month = now()->addMonths($i)->month;
            $year = now()->addMonths($i)->year;
            foreach ($quotes as $quote)
            {
                if ($quote->lead && $quote->lead->forecast_date)
                {
                    if ($quote->lead->forecast_date->month == $month && $quote->lead->forecast_date->year == $year)
                    {
                        $total += $quote->mrr;
                    }
                }
            }
            _imetric(MetricType::TotalMonthForecast, $total, null, null,
                now()->addMonths($i)->startOfMonth()->format("Y-m-d"));
        }
    }

    /**
     * Get ecommerce quotes
     * @return void
     */
    public function totalEcommerceQuote(): void
    {

        $quotes = Quote::whereHas('lead', function ($q) {
            $q->where('guest_created', true);
        })->get();
        $total = 0;
        foreach ($quotes as $quote)
        {
            $total += $quote->total;
        }
        _imetric(MetricType::TotalEcommerceQuote, $total);
    }


    public function totalConvertedMrr(): void
    {
        $total = 0;
        foreach (Quote::where('activated_on', '>=', now()->startOfDay())->get() as $quote)
        {
            $total += $quote->mrr;
        }
        _imetric(MetricType::TotalConvertedMRR, $total);
    }

    /**
     * Tally a count of all services sold
     * @return void
     */
    public function serviceCount(): void
    {
        // Get all sold services.
        foreach (BillItem::where('type', 'services')->get() as $service)
        {
            $total = 0;
            foreach (Account::where('active', true)->get() as $account)
            {
                foreach ($account->items as $item)
                {
                    if ($item->item && $item->item->id == $service->id)
                    {
                        $total += $item->qty;
                    }
                }
            }
            // Log service
            _imetric(MetricType::ServiceCount, $total, null, null, $service->id);
        } // fe service
    }

    /**
     * Tally a count of all services sold by value ($)
     * @return void
     */
    public function serviceAmount(): void
    {
        // Get all sold services.
        foreach (BillItem::where('type', 'services')->get() as $service)
        {
            $total = 0;
            foreach (Account::where('active', true)->get() as $account)
            {
                foreach ($account->items as $item)
                {
                    if ($item->item && $item->item->id == $service->id)
                    {
                        $total += $item->price * $item->qty;
                    }
                }
            }
            // Log service
            _imetric(MetricType::ServiceAmount, $total, null, null, $service->id);
        } // fe service
    }

    /**
     * How many products were sold by qty for today.
     * @return void
     */
    public function productCount(): void
    {
        foreach (BillItem::where('type', 'products')->get() as $product)
        {
            $total = 0;
            foreach (Invoice::whereIn('status', [
                InvoiceStatus::PAID,
                InvoiceStatus::SENT,
                InvoiceStatus::PARTIAL
            ])->get() as $invoice)
            {
                foreach ($invoice->items as $item)
                {
                    if ($item->item && $item->bill_item_id == $product->id)
                    {
                        $total += $item->qty;
                    }
                }
            }
            // Log product count
            _imetric(MetricType::ProductAmount, $total, null, null, $product->id);
        } // fe product
    }

    /**
     * How many products were sold by $ amount for today.
     * @return void
     */
    public function productAmount(): void
    {
        foreach (BillItem::where('type', 'products')->get() as $product)
        {
            $total = 0;
            foreach (Invoice::whereIn('status', [
                InvoiceStatus::PAID,
                InvoiceStatus::SENT,
                InvoiceStatus::PARTIAL
            ])->get() as $invoice)
            {
                foreach ($invoice->items as $item)
                {
                    if ($item->item && $item->bill_item_id == $product->id)
                    {
                        $total += $item->qty * $item->price;
                    }
                }
            }
            // Log product count
            _imetric(MetricType::ProductAmount, $total, null, null, $product->id);
        } // fe product
    }

    /**
     * Get total invoiced amount
     * @return void
     */
    public function invoiced(): void
    {
        $total = 0;
        foreach (Invoice::where('status', '!=', InvoiceStatus::DRAFT)->get() as $invoice)
        {
            $total += $invoice->total;
        }
        _imetric(MetricType::Invoiced, $total);
    }

    /**
     * Outstanding Commissions
     * @return void
     */
    public function outstandingCommissions(): void
    {
        $total = 0;
        foreach (Commission::where('status', '!=', CommissionStatus::Paid)->where('active', true)->get() as $comm)
        {
            $total += $comm->amount;
        }
        _imetric(MetricType::OutstandingCommission, $total);
    }

    /**
     * Get total Commission Amount.
     * @return void
     */
    public function totalCommissions(): void
    {
        $total = 0;
        foreach (Commission::all() as $comm)
        {
            $total += $comm->amount;
        }
        _imetric(MetricType::TotalCommission, $total);
    }


}
