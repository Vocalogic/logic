<?php

namespace App\Operations\Core;

use App\Enums\Core\InvoiceStatus;
use App\Models\Account;
use App\Models\Invoice;
use App\Models\Quote;
use Carbon\Carbon;

/**
 * This class will break down contracted revenue vs non, vs forecasted.
 * It will also build series data for graphs required.
 *
 * This report should take capex costs, opex costs and even forecasted
 * opex/capex based on forecasted quotes.
 */
class CashFlow
{

    public array $mom = [];                                             // Month over month (collection)

    public array $contractedSeries = [];                                      // Build Contracted Graph
    public array $breakdown        = [];
    public int $year = 2022;
    /**
     * Main processing routine
     * @return void
     */
    public function init(): void
    {
        $this->buildMom();                                                     // Build our month over month array structure
        $this->contractedRevenue();                                            // Get contracted Revenue
        $this->uncontractedRevenue();                                          // Get sold quotes with no contract or sig
        $this->fContractedRevenue();                                           // Get forecasted contract Revenue
        $this->fUncontractedRevenue();                                         // Get forecasted uncontracted Revenue
        $this->buildOpex();                                                    // Build Opex Based on Un/Contracted
        $this->buildFOpCapex();                                                // Forecasted Opex and Capex
        $this->actual();                                                       // Actual Revenue for Given month
        $this->buildContractSeries();


    }

    /**
     * Set working year
     * @param int $year
     * @return void
     */
    public function setYear(int $year) : void
    {
        $this->year = $year;
    }

    /**
     * Builds an array for the next 36 months
     * @return void
     */
    private function buildMom(): void
    {
        $year = Carbon::parse($this->year . "-01-01")->startOfYear();
        foreach (range(0, 11) as $mon)
        {
            $key = $year->copy()->addMonths($mon)->startOfMonth()->format("Y-m-d");
            $this->mom[$key] = (object)[
                'contracted'     => 0,                  // Contracted Revenue
                'uncontracted'   => 0,                  // Non-contracted Revenue
                'f_contracted'   => 0,                  // Forecasted Contracted
                'f_uncontracted' => 0,                  // Forecasted uncontracted
                'opex'           => 0,                  // Opex for the month (sold)
                'fopex'          => 0,                  // Forecasted Opex
                'fcapex'         => 0,                  // Forecasted Capex
                'actual'         => 0
            ];
        }
    }

    /**
     * Build Contracted Revenue. This will get all quotes that have
     * a contract_expires date set and an activation date.
     * @return void
     */
    private function contractedRevenue(): void
    {
        foreach (Quote::whereNotNull('activated_on')
                     ->where('account_id', '>', 0)
                     ->whereDate('contract_expires', '>', now())
                     ->get() as $quote)
        {
            // Iterate over our MOM array
            foreach ($this->mom as $key => $data)
            {
                $mon = Carbon::parse($key);

                // NRC Rev
                if ($mon->startOfMonth() == $quote->activated_on->startOfMonth())
                {
                    // If quote was activated in this month.. Add just the NRC.
                    $this->mom[$key]->contracted += $quote->nrc;
                }
                // MRR Rev
                if ($mon < $quote->contract_expires) // Make sure we are only writing during active period
                {
                    $this->mom[$key]->contracted += $quote->mrr;
                }
            } // fe month iterator
        } // fe quote signed
    } // fn

    /**
     * Similar to contracted but we will
     * @return void
     */
    private function uncontractedRevenue(): void
    {
        $curr = 0;
        foreach (Quote::whereNotNull('activated_on')
                     ->where('account_id', '>', 0)
                     ->whereNull('contract_expires')
                     ->get() as $quote)
        {
            // Iterate over our MOM array
            foreach ($this->mom as $key => $data)
            {
                $mon = Carbon::parse($key);
                // NRC Rev
                if ($mon->startOfMonth() == $quote->activated_on->startOfMonth())
                {
                    // If quote was activated in this month.. Add just the NRC.
                    $this->mom[$key]->uncontracted += $quote->nrc;
                }
                // MRR Rev
                $this->mom[$key]->uncontracted += $quote->mrr;
                $curr++;
            } // fe month iterator
        } // fe quote signed

        // Lets also add in services that have no contract that are active right now.
        foreach (Account::where('active', true)->get() as $account)
        {
            foreach ($account->items as $item)
            {
                if (!$item->quote)
                {
                    $curr = 0;
                    foreach ($this->mom as $key => $data)
                    {
                        $mon = Carbon::parse($key);
                        if ($item->created_at->startOfMonth() <= $mon)
                        $this->mom[$key]->uncontracted += $item->qty * $item->price;
                        $curr++;
                    } // fe month iterator
                } // if no quote assigned (i..e uncontracted)
            } // fe account service
        } // fe account active
    }

    /**
     * Get Month over Month Contracted Forecasted Revenue
     * use start forecast date and terminated on contract term
     * @return void
     */
    private function fContractedRevenue()
    {
        foreach (Quote::whereNull('activated_on')
                     ->where('account_id', '=', 0)
                     ->where('term', '>', 0)
                     ->where('preferred', 1)
                     ->get() as $quote)
        {
            if (!$quote->lead->forecast_date) continue;
            $startsOn = $quote->lead->forecast_date->startOfMonth();
            $endsOn = $quote->lead->forecast_date->addMonths($quote->term)->startOfMonth();

            // Iterate over our MOM array
            foreach ($this->mom as $key => $data)
            {
                $mon = Carbon::parse($key);
                if ($mon < $startsOn || $mon >= $endsOn) continue; // Easy break out

                // NRC Rev
                if ($mon->startOfMonth() == $startsOn)
                {
                    // If quote was activated in this month.. Add just the NRC.
                    $this->mom[$key]->f_contracted += $quote->nrc;
                }
                // MRR Rev
                $this->mom[$key]->f_contracted += $quote->mrr;
            } // fe month iterator
        } // fe quote signed
    }

    /**
     * Month over month for 6 months for uncontracted.
     * @return void
     */
    private function fUncontractedRevenue()
    {
        $maxMonths = 12; // Make a setting

        foreach (Quote::whereNull('activated_on')
                     ->where('account_id', '=', 0)
                     ->where('term', '=', 0)
                     ->where('preferred', 1)
                     ->get() as $quote)
        {
            if (!$quote->lead->forecast_date) continue;
            $startsOn = $quote->lead->forecast_date->startOfMonth();
            $endsOn = $quote->lead->forecast_date->addMonths($maxMonths)->startOfMonth();
            // Iterate over our MOM array
            foreach ($this->mom as $key => $data)
            {
                $mon = Carbon::parse($key);
                if ($mon < $startsOn || $mon >= $endsOn) continue; // Easy break out

                // NRC Rev
                if ($mon->startOfMonth() == $startsOn)
                {
                    // If quote was activated in this month.. Add just the NRC.
                    $this->mom[$key]->f_uncontracted += $quote->nrc;
                }
                // MRR Rev
                $this->mom[$key]->f_uncontracted += $quote->mrr;
            } // fe month iterator
        } // fe quote
    }

    /**
     * Build Opex based on either uncontracted maxmonths or
     * contracted end dates.
     * @return void
     */
    private function buildOpex(): void
    {
        foreach (Quote::whereNotNull('activated_on')
                     ->where('account_id', '>', 0)
                     ->whereDate('contract_expires', '>', now())
                     ->get() as $quote)
        {
            // Iterate over our MOM array
            foreach ($this->mom as $key => $data)
            {
                $mon = Carbon::parse($key);
                if ($mon < $quote->contract_expires) // Make sure we are only writing during active period
                {
                    $this->mom[$key]->opex += $quote->opex;
                }
            }
        }
    }

    /**
     * Build Opex based on either uncontracted maxmonths or
     * contracted end dates.
     * @return void
     */
    private function buildFOpCapex(): void
    {
        foreach (Quote::whereNull('activated_on')
                     ->where('account_id', '-', 0)
                     ->where('term', '>', 0)
                     ->where('preferred', 1)
                     ->get() as $quote)
        {
            // Iterate over our MOM array
            foreach ($this->mom as $key => $data)
            {
                $mon = Carbon::parse($key);
                if (!$quote->lead->forecast_date) continue;
                $startsOn = $quote->lead->forecast_date->startOfMonth();
                $endsOn = $quote->lead->forecast_date->addMonths($quote->term)->startOfMonth();
                if ($mon < $startsOn || $mon >= $endsOn) continue; // Easy break out
                $this->mom[$key]->fopex += $quote->opex;
                if ($mon == $startsOn)
                {
                    $this->mom[$key]->fcapex += $quote->capex;
                }
            }
        }

        // Now we are going to do the same but for no term'd quotes.
        $maxMonths = 12; // Make a setting

        foreach (Quote::whereNull('activated_on')
                     ->where('account_id', '-', 0)
                     ->where('term', '=', 0)
                     ->where('preferred', 1)
                     ->get() as $quote)
        {
            // Iterate over our MOM array
            foreach ($this->mom as $key => $data)
            {
                $mon = Carbon::parse($key);
                if (!$quote->lead->forecast_date) continue;
                $startsOn = $quote->lead->forecast_date->startOfMonth();
                $endsOn = $quote->lead->forecast_date->addMonths($maxMonths)->startOfMonth();
                if ($mon < $startsOn || $mon >= $endsOn) continue; // Easy break out
                $this->mom[$key]->fopex += $quote->opex;
                if ($mon == $startsOn)
                {
                    $this->mom[$key]->fcapex += $quote->capex;
                }
            }
        }

    }

    /**
     * Build a series for this year (Jan - Dec)
     * Show contracted, uncontracted Rev as bar
     * show opex as line and fopex line
     * @return void
     */
    private function buildContractSeries()
    {

    }

    /**
     * This method will be used for the table print outs to
     * use a 12 month rolling window. Color red if next year
     * green if current year. Since its june now it would be
     * green in june, and red behind.
     * @return void
     */
    public function iterateYear(): void
    {
        $count = 1;
        foreach ($this->mom as $idx => $data)
        {
            $this->breakdown[$count] = $data;
            $color = Carbon::parse($idx) < now() ? 'danger' : 'success';
            $this->breakdown[$count]->color = $color;
            $count++;
        }
    } // fn


    /**
     * Get actual revenue from paid invoices
     * @return void
     */
    private function actual()
    {
        foreach (Invoice::where('status', InvoiceStatus::PAID)->get() as $invoice)
        {
            // Iterate over our MOM array
            foreach ($this->mom as $key => $data)
            {
                $mon = Carbon::parse($key);
                if ($mon->startOfMonth() == $invoice->paid_on->startOfMonth()) // Make sure we are only writing during active period
                {
                    $this->mom[$key]->actual += $invoice->total;
                }
            }
        }
    }


}
