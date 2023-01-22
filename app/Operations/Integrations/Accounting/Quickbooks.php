<?php

namespace App\Operations\Integrations\Accounting;

use App\Enums\Core\IntegrationRegistry;
use App\Exceptions\LogicException;
use App\Models\Account;
use App\Models\BillCategory;
use App\Models\BillItem;
use App\Models\Invoice;
use App\Models\Transaction;
use App\Operations\API\QBO\QBOCategory;
use App\Operations\API\QBO\QBOCore;
use App\Operations\API\QBO\QBOCustomer;
use App\Operations\API\QBO\QBOInvoice;
use App\Operations\API\QBO\QBOPayment;
use App\Operations\API\QBO\QBOService;
use App\Operations\Integrations\BaseIntegration;
use App\Operations\Integrations\Integration;
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
        return "https://gdm-catalog-fmapi-prod.imgix.net/ProductLogo/b14a841d-1dc7-47fc-bdaa-8dc4c7869fa7.png";
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
     */
    public function syncAccount(Account $account): void
    {
        $customer = new QBOCustomer($this->config);
        $customer->byAccount($account);
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

}
