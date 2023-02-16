<?php

namespace App\Operations\Admin;

use App\Models\Account;
use App\Models\Invoice;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\User;

/**
 * This class will perform profitability analysis on a per-account and per-quote basis
 */
class AnalysisEngine
{

    static public object $accountData;

    /**
     * Determine profitability by quote including commissions and capex.
     * @param Quote $quote
     * @return object
     */
    static public function byQuote(Quote $quote): object
    {
        $mrr = $quote->mrr;
        $nrc = $quote->nrc;
        $term = $quote->term ?: (int)setting('quotes.assumedTerm');
        $opexSolo = 0;

        // Gross Income.
        $grossIncome = ($mrr * $term) + $nrc;
        // Capex
        $capex = 0;
        foreach ($quote->products as $product)
        {
            if ($product->item->ex_capex_once)
            {
                $capex += $product->item->ex_capex;
            }
            else
            {
                $capex += $product->item->ex_capex * $product->qty;
            }
            // Check for any addons that may have an expense.
            foreach ($product->addons as $addon)
            {
                if ($addon->option && $addon->option->item)
                {
                    $capex += $addon->option->item->ex_capex * $addon->qty;
                }
            }
        }

        $opex = 0;
        foreach ($quote->services as $service)
        {
            if ($service->item->ex_opex_once)
            {
                $opex += $service->item->ex_opex * $term;
            }
            else
            {
                $opex += ($service->item->ex_opex * $service->qty) * $term;
            }
            $opexSolo += $service->item->ex_opex * $service->qty;

            // Get addon opex
            foreach ($service->addons as $addon)
            {
                if ($addon->option && $addon->option->item)
                {
                    if ($addon->option->item->ex_opex_once)
                    {
                        $opex += $addon->option->item->ex_capex * $term;
                    }
                    else
                    {
                        $opex += ($addon->option->item->ex_opex * $addon->qty) * $term;
                    }
                    $opexSolo += $addon->option->item->ex_opex * $addon->qty;
                }
            }

        }
        $agentComm = 0;
        $agentMonthly = 0;
        $agentSpiff = 0;

        if ($quote->lead && $quote->lead->agent) // This is not a sold account yet.
        {
            if ($quote->lead->agent->agent_comm_spiff)
            {
                $agentSpiff = $quote->lead->agent->agent_comm_spiff * $mrr;
            }
            if ($quote->lead->agent->agent_comm_mrc)
            {
                // #94 - Deduct Expenses Check for Commissionable Amount
                if (setting('quotes.subtractExpense') == 'Yes')
                {
                    // We need to subtract OpexSolo (not over term)
                    $subtractedMrr = $mrr - $opexSolo; // This is our base calculation for monthly
                    $subtractedTerm = $subtractedMrr * $term;
                    $agentMonthly = $subtractedMrr * ($quote->lead->agent->agent_comm_mrc / 100);
                    $agentComm = $subtractedTerm * ($quote->lead->agent->agent_comm_mrc / 100);
                }
                else
                {
                    // Get MRC * term. (standard without subtracting expenses)
                    $termMrr = $mrr * $term;
                    $agentMonthly = $mrr * ($quote->lead->agent->agent_comm_mrc / 100);
                    $agentComm = $termMrr * ($quote->lead->agent->agent_comm_mrc / 100);
                }
            }
        }

        // Now we need to figure out commission. if its a spiff add it like a capex.
        // If its a mrr commission then it needs to be calulated like an opex.
        if (setting('quotes.commexp') == 'Yes')
        {
            $netValue = $grossIncome - ($capex + $opex + $agentComm + $agentSpiff);
        }
        else
        {
            $netValue = $grossIncome - ($capex + $opex);
        }
        $margin = 0;

        if ($grossIncome > 0)
        {
            $margin = round($netValue / $grossIncome * 100, 2);
        }

        // Month Profitability. This is a basic subtract capex and first month opex then figure out how many months until green
        return (object)[
            'gross'             => $grossIncome,
            'capex'             => $capex,
            'opex'              => $opex,
            'margin'            => $margin,
            'profit'            => $netValue,
            'totalCommission'   => $agentComm,
            'monthlyCommission' => $agentMonthly,
            'agentSpiff'        => $agentSpiff,
            'opexSolo'          => $opexSolo
        ];
    }

    /**
     * Get ongoing profitability by Account
     * @param Account $account
     * @return object
     */
    static public function byAccount(Account $account): object
    {
        if (isset(self::$accountData) && self::$accountData != null) return self::$accountData;
        $data = (object)[
            'services'  => [],
            'total'     => 0,
            'invoiced'  => 0,
            'remaining' => 0,
            'opex'      => 0,
            'margin'    => 0
        ];

        // Step 1: Iterate Account Services
        // Step 2: If service has a quote check if contracted.
        // Step 2b: If contracted, total MRR is only calculated with the amount of time left on a contract.
        // Step 2c: Otherwise use quotes.assumedTerm FROM start of service (created_at)
        // Lets also collect income for each service to show how much has been realized and how much is tentatively left.

        foreach ($account->items as $service)
        {
            $serviceTotal = 0;
            $serviceInvoiced = 0;
            $serviceRemaining = 0;
            $serviceOpex = 0;
            if ($service->quote)
            {
                $monthsFromStart = $service->quote->term;
                if ($monthsFromStart == 0) $monthsFromStart = (int)setting('quotes.assumedTerm');
            }
            else
            {
                $monthsFromStart = (int)setting('quotes.assumedTerm');
            }
            $serviceOpex += $monthsFromStart * ($service->item->ex_opex * $service->qty);

            $serviceTotal += $monthsFromStart * ($service->price * $service->qty);
            foreach ($account->invoices as $invoice)
            {
                foreach ($invoice->items as $item)
                {
                    if ($item->bill_item_id == $service->bill_item_id)
                    {
                        $serviceInvoiced += $item->price * $item->qty;
                    }
                } // fe item
            } // fe invoice
            $serviceRemaining += $serviceTotal - $serviceInvoiced;
            if ($serviceRemaining < 0) $serviceRemaining = 0;
            if ($serviceTotal > 0)
            {
                $perc = round(($serviceRemaining / $serviceTotal) * 100, 2);
            }
            else $perc = 0;
            if ($perc > 50)
            {
                $color = 'bg-success';
            }
            else
            {
                if ($perc < 50 && $perc > 25)
                {
                    $color = 'bg-warning';
                }
                else $color = 'bg-danger';
            }

            $data->services[] = (object)[
                'service'   => $service,
                'total'     => $serviceTotal,
                'invoiced'  => $serviceInvoiced,
                'remaining' => $serviceRemaining,
                'barColor'  => $color,
                'barPerc'   => $perc
            ];
            $data->total += $serviceTotal;
            $data->invoiced += $serviceInvoiced;
            $data->remaining += $serviceRemaining;
            $data->opex += $serviceOpex;
        } // fe service
        $diff = $data->total - $data->opex;
        if ($data->total > 0)
        {
            $margin = round($diff / $data->total * 100, 2);
        }
        else $margin = 100;
        $data->margin = $margin;
        self::$accountData = $data;
        return $data;
    }

    /**
     * Get agent commissionable amount on a per item basis.
     * @param User      $user
     * @param QuoteItem $item
     * @return int
     */
    static public function byQuoteItem(User $user, QuoteItem $item): int
    {
        $comm = $user->agent_comm_mrc;
        if (!$comm) return 0;
        $basePrice = setting('quotes.subtractExpense') == 'Yes'
            ? $item->price - $item->item->ex_opex
            : $item->price;
        $total = $basePrice * $item->qty;
        return $total * ($comm / 100);
    }

    /**
     * This method will return the commissionable amount on an invoice.
     * This should only be called if the invoice is commissionable (also recurring invoice)
     * @param Invoice $invoice
     * @return int
     */
    static public function byInvoice(Invoice $invoice): int
    {
        // Get MRR totals.
        $amount = 0;
        $total = $invoice->total; // Total of Recurring Invoice

        // First lets figure out how much is actually commissionable.
        if (setting('quotes.subtractExpense') == 'Yes')
        {
            $totalExpenses = 0;
            foreach($invoice->items as $item)
            {
                if($item->item && $item->item->ex_opex)
                {
                    $totalExpenses += bcmul($item->item->ex_opex * $item->qty,1);
                }
            }
            $commissionable = $total - $totalExpenses;
        }
        else
        {
            $commissionable = $total;
        }

        // #154 - Affiliate Commissioning
        if ($invoice->account->affiliate)
        {
            if ($invoice->account->affiliate->mrr > 0)
            {
                $per = $invoice->account->affiliate->mrr / 100;
                $amount = $commissionable * $per;
            }
            elseif($invoice->account->affiliate->spiff)
            {
                // Check if already spiffed
                if ($invoice->account->spiffed) return 0;
                $amount = $total * $invoice->account->affiliate->spiff;
            }
            return $amount;
        }

        // If we are here, then no affiliate found and it's commissionable by agent.
        if ($invoice->account->agent->agent_comm_mrc > 0)
        {
            $per = $invoice->account->agent->agent_comm_mrc / 100; // Take 10 and make it .1
            $amount = $commissionable * $per;
        }
        elseif ($invoice->account->agent->agent_comm_spiff > 0 && !$invoice->account->spiffed)
        {
            $amount = $total * $invoice->account->agent->agent_comm_spiff;
        }
        return $amount;
    }
}
