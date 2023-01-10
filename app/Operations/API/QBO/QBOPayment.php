<?php

namespace App\Operations\API\QBO;

use App\Enums\Core\PaymentMethod;
use App\Models\Transaction;
use GuzzleHttp\Exception\GuzzleException;

class QBOPayment extends QBOCore
{
    public string $methodCacheKey = 'QBO_METHODS';
    /**
     * Get a list of all payments
     * @return mixed
     * @throws GuzzleException
     */
    public function all(): mixed
    {
        return $this->qsend("query", 'get', [
            'query' => "SELECT * from Payment"
        ]);
    }

    /**
     * Query QBO for Payment Types and then match with our enums.
     * @param Transaction $transaction
     * @return int
     * @throws GuzzleException
     */
    public function getPaymentTypeByTransaction(Transaction $transaction) : int
    {
        // This should be cached and only pulled in the event of a cache dump.
        if (cache($this->methodCacheKey))
        {
            $methods = cache($this->methodCacheKey);
        }
        else
        {
            $methods = $this->qsend("query", 'get', [
                'query' => "SELECT * from PaymentMethod"
            ]);
            cache([$this->methodCacheKey => $methods], now()->addYear());
        }

        foreach ($methods->QueryResponse->PaymentMethod as $method)
        {
            $pw = $transaction->method instanceof PaymentMethod ? $transaction->method->value : $transaction->method;
            if (preg_match("/$pw/i", $method->Name))
            {
                return $method->Id;
            }
        }
        return 1; // I mean.. assume it's credit card I guess? Who knows at this point.
    }

    /**
     * Sync a transaction
     * @param Transaction $transaction
     * @return void
     * @throws GuzzleException
     */
    public function byTransaction(Transaction $transaction) : void
    {
        $data = (object)[];
        // Set Amount
        $data->TotalAmt = sprintf("%.2f", $transaction->amount);
        // Set Transaction Date
        $data->TxnDate = $transaction->created_at->format("Y-m-d");
        // Build Customer Reference Object
        $data->CustomerRef = (object)[
            'value' => $transaction->account->finance_customer_id
        ];
        // Build Line Transaction Array
        $data->Line = [];
        // Build Singular Line to apply to our data line
        $line = (object)[];
        $line->Amount = sprintf("%.2f", $transaction->amount);
        // Link Transaction to Invoice
        $line->LinkedTxn = [];
        $line->LinkedTxn[] = (object)[
            'TxnId'   => $transaction->invoice->finance_invoice_id,
            'TxnType' => 'Invoice'
        ];
        // Apply to Master Line Item
        $data->Line[] = $line;

        // Define Our Payment Method.
        $data->PaymentMethodRef = (object)[
            'value' => $this->getPaymentTypeByTransaction($transaction)
        ];
        // Set our Reference number - This is our local Logic transaction
        $data->PaymentRefNum = $transaction->id;

        $res = $this->qsend("payment", 'post', (array)$data);
        $transaction->update(['finance_transaction_id' => $res->Payment->Id]);
    }

}
