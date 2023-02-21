<?php

namespace App\Operations\Integrations\Accounting;

use App\Enums\Core\IntegrationRegistry;
use App\Exceptions\LogicException;
use App\Models\Account;
use App\Models\BillCategory;
use App\Models\BillItem;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Quote;
use App\Models\Transaction;
use App\Operations\API\QBO\QBOCategory;
use App\Operations\API\QBO\QBOCore;
use App\Operations\API\QBO\QBOCustomer;
use App\Operations\API\QBO\QBOEstimate;
use App\Operations\API\QBO\QBOInvoice;
use App\Operations\API\QBO\QBOPayment;
use App\Operations\API\QBO\QBOService;
use App\Operations\Integrations\BaseIntegration;
use App\Operations\Integrations\Integration;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;

class Quickbooks extends BaseIntegration implements Integration
{
    public IntegrationRegistry $ident = IntegrationRegistry::QuickbooksOnline;
    public QBOCore             $qbo;

    /**
     * Bind Quickbooks API Class
     */
    public function __construct()
    {
        parent::__construct();
        $this->qbo = new QBOCore($this->config);
    }

    /**
     * Get application name
     * @return string
     */
    public function getName(): string
    {
        return "Quickbooks";
    }

    /**
     * Get the website for the integration
     * @return string
     */
    public function getWebsite(): string
    {
        return "https://quickbooks.intuit.com";
    }

    /**
     * Get description
     * @return string
     */
    public function getDescription(): string
    {
        return "Easily track income, expenses, and more with accounting software designed for all kinds of businesses.";
    }

    /**
     * Get Logo
     * @return string
     */
    public function getLogo(): string
    {
        return "/assets/images/integrations/qbo.png";
    }

    /**
     * Get required configuration for slack.
     * @return object[]
     */
    public function getRequired(): array
    {
        return [
            (object)[
                'var'         => 'qbo_cid',
                'item'        => "Quickbooks Company ID:",
                'description' => "This is populated from the app authorization",
                'default'     => '',
                'protected'   => false,
            ],
            (object)[
                'var'         => 'qbo_default_income',
                'item'        => "Default Income Account:",
                'description' => "This is populated from quickbooks and can be changed if required",
                'default'     => '',
                'protected'   => false,
            ],
            (object)[
                'var'         => 'qbo_client_id',
                'item'        => "Quickbooks Client ID:",
                'description' => "Enter your production client ID",
                'default'     => '',
                'protected'   => false,
            ],
            (object)[
                'var'         => 'qbo_client_secret',
                'item'        => "Quickbooks Client Secret:",
                'description' => "Enter your production client Secret",
                'default'     => '',
                'protected'   => true,
            ],
            (object)[
                'var'         => 'use_integration_tax',
                'item'        => "Use Quickbooks Automated Sales Tax:",
                'description' => "If you have enabled Quickbooks AST engine enter Y.",
                'default'     => 'Y',
                'protected'   => false,
            ],

        ];
    }
    /** -------------------- End of Configuration ---------------------- */

    // Quickbooks OAuth Registration Information
    /**
     * Generate the Oauth Redirect to QBO URL
     * @return string
     */
    public function getOauthRedirect(): string
    {
        return $this->qbo->getRedirectUrl();
    }

    /**
     * Process the callback authorization from QBO
     * @param Request $request
     * @return void
     * @throws LogicException
     */
    public function processCallback(Request $request): void
    {
        $this->qbo->processCallback($request);
    }

    /**
     * This method sync's a customer with quickbooks. If a Customer
     * already exists then it will update the record, sync the id
     * down, or create a new company and sync.
     * @param Account $account
     * @return void
     * @throws GuzzleException
     * @throws LogicException
     */
    public function syncAccount(Account $account): void
    {
        $customer = new QBOCustomer($this->config);
        $customer->byAccount($account);
    }

    /**
     * Create or update an account by lead. (presales)
     * @param Lead $lead
     * @return void
     * @throws GuzzleException
     * @throws LogicException
     */
    public function syncLead(Lead $lead) : void
    {
        $customer = new QBOCustomer($this->config);
        $customer->byLead($lead);
    }

    /**
     * If a lead was lost then we need to remove this account from quickbooks.
     * @param Lead $lead
     * @return void
     * @throws LogicException|Exception
     */
    public function removeLead(Lead $lead): void
    {
        if (!$lead->finance_customer_id) return;
        $customer = new QBOCustomer($this->config);
        $customer->remove($lead->finance_customer_id);
    }

    /**
     * Sync a bill category with a quickbooks category
     * @param BillCategory $category
     * @return void
     * @throws GuzzleException
     */
    public function syncCategory(BillCategory $category): void
    {
        $cat = new QBOCategory($this->config);
        $cat->byCategory($category);
    }

    /**
     * Sync a bill item with a quickbooks service
     * @param BillItem $item
     * @return void
     * @throws GuzzleException
     */
    public function syncItem(BillItem $item): void
    {
        $cat = new QBOService($this->config);
        $cat->byItem($item);
    }

    /**
     * Sync Invoice
     * @param Invoice $invoice
     * @return void
     * @throws GuzzleException
     * @throws LogicException
     */
    public function syncInvoice(Invoice $invoice): void
    {
        $inv = new QBOInvoice($this->config);
        $inv->byInvoice($invoice);
    }

    /**
     * Remove an invoice
     * @param Invoice $invoice
     * @return void
     * @throws GuzzleException
     * @throws LogicException
     */
    public function deleteInvoice(Invoice $invoice): void
    {
        $inv = new QBOInvoice($this->config);
        if (!$invoice->finance_invoice_id) return;
        $inv->deleteBy($invoice);
    }

    /**
     * Sync a payment (transaction) to Quickbooks
     * @param Transaction $transaction
     * @return void
     * @throws GuzzleException
     */
    public function syncTransaction(Transaction $transaction): void
    {
        $x = new QBOPayment($this->config);
        if ($transaction->finance_transaction_id) return;
        $x->byTransaction($transaction);
    }

    /**
     * This method will query QBO's AST System by creating an estimate if not
     * found, and then getting the tax associated from Quickbooks and return.
     * @param Quote $quote
     * @return float
     * @throws GuzzleException
     * @throws LogicException
     */
    public function taxByQuote(Quote $quote): float
    {
        // Step 1: Do we have a finance_customer_id on leads. If not this is probably the first
        // quote. So we will need to create the account in Quickbooks first so we can sync the
        // estimate for taxation.
        if ($quote->lead && !$quote->lead->finance_customer_id)
        {
            $this->syncLead($quote->lead);
        }
        if ($quote->account && !$quote->account->finance_customer_id)
        {
            $this->syncAccount($quote->account);
        }
        $quote->refresh(); // Make sure we have our new customer id there.
        // Step 2: We need to sync our quote to Quickbooks as an Estimate
        $x = new QBOEstimate($this->config);
        $est = $x->byQuote($quote);
        if ($est && isset($est->TxnTaxDetail) && isset($est->TxnTaxDetail->TotalTax))
        {
            return $est->TxnTaxDetail->TotalTax;
        }
        return 0;
    }

    /**
     * This method will query QBO's AST System by a sync'd invoice if not
     * found, and then getting the tax associated from Quickbooks and return.
     * @param Invoice $invoice
     * @return float
     * @throws GuzzleException
     * @throws LogicException
     */
    public function taxByInvoice(Invoice $invoice): float
    {
        $inv = new QBOInvoice($this->config);
        $qi = $inv->byInvoice($invoice);
        if ($qi && isset($qi->TxnTaxDetail) && isset($qi->TxnTaxDetail->TotalTax))
        {
            return $qi->TxnTaxDetail->TotalTax;
        }
        return 0;
    }

}
