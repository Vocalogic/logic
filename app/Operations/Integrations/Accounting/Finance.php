<?php

namespace App\Operations\Integrations\Accounting;

use App\Enums\Core\IntegrationType;
use App\Models\Account;
use App\Models\BillCategory;
use App\Models\BillItem;
use App\Models\Invoice;
use App\Models\Transaction;
use Exception;

class Finance
{

    public IntegrationType $type = IntegrationType::Finance;


    /**
     * Called from Observers to sync account. Should create new merchant account
     * or update our account with the proper customer id/token
     *
     * @param Account $account
     * @return void
     */
    static public function syncAccount(Account $account): void
    {
        $x = new self;
        $x->syncCustomer($account);
    }

    /**
     * Sync invoice to 3rd party finance systems.
     * @param Invoice $invoice
     * @return void
     */
    static public function syncInvoice(Invoice $invoice): void
    {
        try
        {
            if (!$invoice->items()->count()) return;
            $x = new self;
            $x->syncInv($invoice);
        } catch (Exception $e)
        {
            info("Failed to Sync Invoice to Accounting Provider ($invoice->id) - " . $e->getMessage());
        }
    }

    /**
     * Remove an invoice
     * @param Invoice $invoice
     * @return void
     */
    static public function deleteInvoice(Invoice $invoice): void
    {
        $x = new self;
        $x->deleteInv($invoice);
    }

    /**
     * Sync Customer Account.
     * @param Account $account
     * @return void
     */
    public function syncCustomer(Account $account): void
    {
        if ($account->admin)
        {
            try
            {
                getIntegration($this->type)->connect()->syncAccount($account);
            } catch (Exception $e)
            {
                info("Could not sync Account $account->id to accounting: " . $e->getMessage());
            }
        }
    }

    /**
     * Sync Invoice
     * @param Invoice $invoice
     * @return void
     */
    public function syncInv(Invoice $invoice): void
    {
        try
        {
            getIntegration($this->type)->connect()->syncInvoice($invoice);
        } catch (Exception $e)
        {
            info("Could not sync Invoice $invoice->id to Accounting: " . $e->getMessage());
        }
    }

    /**
     * Remove an Invoice
     * @param Invoice $invoice
     * @return void
     */
    public function deleteInv(Invoice $invoice): void
    {
        try
        {
            getIntegration($this->type)->connect()->deleteInvoice($invoice);
        } catch (Exception $e)
        {
            info("Could not delete invoice $invoice->id from accounting: " . $e->getMessage());
        }
    }

    /**
     * Sync a category with a product catalog category.
     * @param BillCategory $category
     * @return void
     */
    static public function syncCategory(BillCategory $category): void
    {
        try
        {
            $x = new self;
            getIntegration($x->type)->connect()->syncCategory($category);
        } catch (Exception $e)
        {
            info("Could not sync category $category->id to accounting: " . $e->getMessage());
        }
    }

    /**
     * Sync billable item to a finance provider.
     * @param BillItem $item
     * @return void
     */
    static public function syncItem(BillItem $item): void
    {
        try
        {
            $x = new self;
            getIntegration($x->type)->connect()->syncItem($item);
        } catch (Exception $e)
        {
            info("Could not sync item $item->id to Accounting: " . $e->getMessage());
        }
    }

    /**
     * Sync a transaction to a finance provider.
     * @param Transaction $transaction
     * @return void
     */
    static public function syncTransaction(Transaction $transaction): void
    {
        $x = new self;
        try
        {
            getIntegration($x->type)->connect()->syncTransaction($transaction);
        } catch (Exception $e)
        {
            info("Unable to sync transaction $transaction->id to Accounting : " . $e->getMessage());
        }
    }

}
