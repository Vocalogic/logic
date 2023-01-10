<?php

namespace App\Enums\Core;

/**
 * This enum will track all of the storable metrics that
 * can be used for building graphs over certain time periods.
 */
enum MetricType: string
{
    // Sales Related Metrics
    case TotalLeads = 'LEADS_TOTAL';                                // Total Number of Active Leads
    case TotalQuoted = 'QUOTE_TOTAL';                               // Total number of open quotes preferred
    case TotalQuotedValue = 'QUOTE_VALUE';                          // Total Amount in Quotes MRR + NRC
    case TotalQuoteMRR = 'QUOTE_MRR';                               // Total Amount open in MRR
    case TotalConvertedMRR = "CONVERTED_MRR";                       // Quotes that were sold in a day
    case TotalQuoteNRC = 'QUOTE_NRC';                               // Total Amount open in NRC
    case TotalLost = 'LEAD_LOST';                                   // Total Lost Leads
    case LeadsTouched = 'LEADS_TOUCHED';                            // Number of Leads with Activity for a period
    case LeadActivity = 'LEAD_ACTIVITY';                            // Number of Activity (photos, comments, etc on a lead)
    case TotalForecasted = 'QUOTE_FORECAST';                        // Amount currently forecasted
    case TotalMonthForecast = 'QUOTE_MONTH_FORECAST';               // Amount forecasted in this month.
    case TotalEcommerceQuote = 'QUOTE_MONTH_ECOMMERCE';             // Amount quoted via self-service


    // Account Related Metrics
    case MRR = 'MRR';                                               // Total MRR Company Wide
    case Invoiced = 'INVOICED';                                     // Total Invoice Open Amount
    case AccountTotalInvoiced = "INV_TOTAL_ACCOUNT";                // Total Invoiced Amount
    case AccountMRR = "MRR_ACCOUNT";                                // MRR For each Account Broken Down
    case AccountInvoiced = "INV_ACCOUNT";                           // Amount invoiced for a day.
    case TotalOutstanding = 'TOTAL_OUTSTANDING';                    // Outstanding off of open invoices.

    // Product/Service Metrics

    case ServiceCount = "SERVICE_SOLD_COUNT";                       // As of day, how many of each service is sold
    case ProductCount = "PRODUCT_SOLD_COUNT";                       // As of day how many of each product has been sold
    case ServiceAmount = "SERVICE_SOLD_AMOUNT";                     // How much in value has been sold
    case ProductAmount = "PRODUCT_SOLD_AMOUNT";                     // Same.. for products

    // Commissions
    case OutstandingCommission = "OUTSTANDING_COMMISSION";          // How many commissions are scheduled to be paid
    case TotalCommission = "TOTAL_COMMISSION";                      // Total Commission

    /**
     * Return the collector method used to generate this data.
     * @return string
     */
    public function getCollector(): string
    {
        return match ($this)
        {
            self::TotalLeads => 'totalLeads',
            self::TotalQuoted => 'totalQuoted',
            self::TotalQuotedValue => 'totalQuotedValue',
            self::TotalQuoteMRR => 'totalQuotedMrr',
            self::TotalConvertedMRR => 'totalConvertedMrr',
            self::TotalQuoteNRC => 'totalQuotedNrc',
            self::TotalLost => 'totalLost',
            self::LeadsTouched => 'leadsTouched',
            self::LeadActivity => 'leadActivity',
            self::MRR => 'totalMrr',
            self::AccountMRR => 'accountMrr',
            self::AccountInvoiced => 'accountInvoiced',
            self::TotalOutstanding => 'totalOutstanding',
            self::TotalForecasted => 'totalForecasted',
            self::TotalMonthForecast => 'totalMonthForecast',
            self::TotalEcommerceQuote => 'totalEcommerceQuote',
            self::AccountTotalInvoiced => 'accountTotalInvoiced',
            self::ServiceCount => "serviceCount",
            self::ProductCount => "productCount",
            self::ServiceAmount => "serviceAmount",
            self::ProductAmount => "productAmount",
            self::Invoiced => "invoiced",
            self::OutstandingCommission => "outstandingCommissions",
            self::TotalCommission => "totalCommissions",
        };
    }

    /**
     * Get the series name based on type
     * @param string|null $specific csv of any replacements
     * @return string
     */
    public function getSeriesName(?string $specific = null): string
    {
        $text = match ($this)
        {
            self::TotalLeads => "Total Leads",
            self::TotalQuoted => "Amount Quoted",
            self::TotalQuotedValue => "Quoted Value",
            self::TotalQuoteMRR => "Quoted MRR",
            self::TotalConvertedMRR => "Converted MRR",
            self::TotalQuoteNRC => "Quoted NRC",
            self::TotalLost => "Leads Lost",
            self::LeadsTouched => "Leads Updated",
            self::LeadActivity => "Lead Activity",
            self::MRR => "Monthly Recurring",
            self::AccountMRR => "Account MRR",
            self::AccountInvoiced => "Open Invoice Amount",
            self::AccountTotalInvoiced => "Account Total Invoiced",
            self::TotalOutstanding => "Outstanding Amount",
            self::TotalForecasted => "Total Forecasted",
            self::TotalEcommerceQuote => "Quoted via Self-Service",
            self::ServiceCount, self::ProductCount, self::ProductAmount, self::ServiceAmount => "%s",
            self::TotalMonthForecast => "Total Monthly Forecasted",
            self::Invoiced => "Total Invoiced",
            self::OutstandingCommission => "Total Outstanding",
            self::TotalCommission => "Total Commissions",
        };
        $replacements = explode(",", $specific);
        return vsprintf($text, $replacements);
    }

    /**
     * Define Metrics that should be converted to money formats when presenting
     * to a graph or other metric display.
     * @return bool
     */
    public function isMoney(): bool
    {
        return match ($this)
        {
            self::TotalQuoteMRR,
            self::TotalCommission,
            self::OutstandingCommission,
            self::Invoiced,
            self::TotalMonthForecast,
            self::TotalEcommerceQuote,
            self::TotalOutstanding,
            self::AccountTotalInvoiced,
            self::AccountInvoiced,
            self::TotalForecasted,
            self::AccountMRR,
            self::MRR,
            self::TotalQuoteNRC,
            self::TotalConvertedMRR,
            self::TotalQuoted,
            self::TotalQuotedValue => true,
            default => false
        };
    }
}
