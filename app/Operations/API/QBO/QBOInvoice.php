<?php

namespace App\Operations\API\QBO;

use App\Exceptions\LogicException;
use App\Models\Invoice;
use GuzzleHttp\Exception\GuzzleException;

class QBOInvoice extends QBOCore
{
    /**
     * Get all invoices
     * @return mixed
     * @throws GuzzleException|LogicException
     */
    public function all(): mixed
    {
        return $this->qsend("query", 'get', ['query' => "SELECT * from Invoice"]);
    }

    /**
     * Find Invoice by Id
     * @param int $id
     * @return mixed
     * @throws GuzzleException|LogicException
     */
    public function find(int $id): mixed
    {
        $res = $this->qsend("query", 'get', [
            'query' => "SELECT * from Invoice where Id='$id'"
        ]);
        if (isset($res->QueryResponse->Invoice))
        {
            return $res->QueryResponse->Invoice[0];
        }
        return null;
    }

    /**
     * Create or Update an Invoice. Will get SyncToken first if an invoice
     * link already exists.
     * @param Invoice $invoice
     * @return mixed
     * @throws GuzzleException
     * @throws LogicException
     */
    public function byInvoice(Invoice $invoice): mixed
    {
        if ($invoice->items()->count() == 0) return null;
        if ($invoice->finance_invoice_id)
        {
            $inv = $this->find($invoice->finance_invoice_id);
        }
        else
        {
            $inv = (object)[];
        }

        // We will build this just as we would a new invoice. The only difference is that
        // if we have an invoice, we pulled the invoice first to get the syncToken in the object.

        // Step 1 - Set basic invoice properties.
        $inv->CustomerRef = (object)['value' => $invoice->account->finance_customer_id];
        $inv->DueDate = $invoice->due_on->format("Y-m-d");
        $inv->DocNumber = $invoice->id;
        // Step 2 - Set Invoice Lines from Invoice
        $lines = [];
        foreach ($invoice->items as $item)
        {
            $line = (object)[];
            $desc = strip_tags($item->description);
            if ($item->code)
            {
                $nameFormatted = sprintf("[%s] %s - %s", $item->code, $item->name, $desc);
            }
            else
            {
                $nameFormatted = sprintf("%s - %s", $item->name, $desc);
            }
            $line->DetailType = "SalesItemLineDetail";
            $line->SalesItemLineDetail = (object)[];

            if ($item->item && $item->item->finance_item_id)
            {
                $line->SalesItemLineDetail->ItemRef = (object)[
                    'name'  => $item->item->name,
                    'value' => $item->item->finance_item_id,
                ];
            }
            else
            {
                $line->SalesItemLineDetail->ItemRef = (object)[
                    'value' => 1,
                ];
            }
            $line->SalesItemLineDetail->Qty = $item->qty;
            $line->SalesItemLineDetail->UnitPrice = moneyFormat($item->price, false);
            $line->Description = $nameFormatted;
            $line->Amount = moneyFormat((int) bcmul($item->qty * $item->price,1), false);
            // Finally add it to our array.
            $lines[] = $line;
        }
        $inv->Line = $lines;
        // Step 3 - Submit to Quickbooks
        $res = $this->qsend("invoice", 'post', (array)$inv);
        if (isset($res->Invoice) && isset($res->Invoice->Id))
        {
            $invoice->update(['finance_invoice_id' => $res->Invoice->Id]);
            $invoice->refresh();
            return $res->Invoice;
        }
        return null;
    }

    /**
     * Remove an invoice.
     * @param Invoice $invoice
     * @return void
     * @throws GuzzleException|LogicException
     */
    public function deleteBy(Invoice $invoice): void
    {
        $token = $this->find($invoice->finance_invoice_id)->SyncToken;
        $this->qsend("invoice?operation=delete", 'post', [
            'SyncToken' => $token,
            'Id'        => $invoice->finance_invoice_id
        ]);
    }

}
