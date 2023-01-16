<?php

namespace App\Operations\Admin;

use App\Models\Account;
use App\Models\Quote;

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
                $capex += moneyFormat($product->item->ex_capex, false);
            }
            else
            {
                $capex += moneyFormat($product->item->ex_capex * $product->qty, false);
            }
            // Check for any addons that may have an expense.
            foreach ($product->addons as $addon)
            {
                if ($addon->option && $addon->option->item)
                {
                    $capex += moneyFormat($addon->option->item->ex_capex * $addon->qty, false);
                }
            }
        }

        $opex = 0;
        foreach ($quote->services as $service)
        {
            if ($service->item->ex_opex_once)
            {
                $opex += moneyFormat($service->item->ex_opex * $term, false);
            }
            else
            {
                $opex += moneyFormat(($service->item->ex_opex * $service->qty) * $term, false);
            }
            $opexSolo += moneyFormat(($service->item->ex_opex * $service->qty), false);

            // Get addon opex
            foreach ($service->addons as $addon)
            {
                if ($addon->option && $addon->option->item)
                {
                    if ($addon->option->item->ex_opex_once)
                    {
                        $opex += moneyFormat($addon->option->item->ex_capex * $term, false);
                    }
                    else
                    {
                        $opex += moneyFormat(($addon->option->item->ex_opex * $addon->qty) * $term, false);
                    }
                    $opexSolo += moneyFormat(($addon->option->item->ex_opex * $addon->qty), false);
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
                $agentSpiff = round($quote->lead->agent->agent_comm_spiff * $mrr, 2);
            }
            if ($quote->lead->agent->agent_comm_mrc)
            {
                // Get MRC * term.
                $termMrr = $mrr * $term;
                $agentMonthly = $mrr * ($quote->lead->agent->agent_comm_mrc / 100);
                $agentComm = $termMrr * ($quote->lead->agent->agent_comm_mrc / 100);
                // Get MRC %
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
                $perc = round(($serviceRemaining / $serviceTotal) * 100);
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
            $margin = round(($diff / $data->total) * 100);
        }
        else $margin = 100;
        $data->margin = $margin;
        self::$accountData = $data;
        return $data;
    }


}
