<?php

namespace App\Operations\API\QBO;

use App\Exceptions\LogicException;
use App\Models\Quote;
use GuzzleHttp\Exception\GuzzleException;

class QBOEstimate extends QBOCore
{

    /**
     * Get all estimates
     * @return mixed
     * @throws LogicException
     * @throws GuzzleException
     */
    public function all(): mixed
    {
        $res = $this->qsend("query", 'get', ['query' => "SELECT * from estimate"]);
        if (isset($res->QueryResponse) && isset($res->QueryResponse->Estimate))
        {
            return $res->QueryResponse->Estimate;
        }
        else return [];
    }

    /**
     * Find Estimate by Id
     * @param int $id
     * @return mixed
     * @throws GuzzleException|LogicException
     */
    public function find(int $id): mixed
    {
        $res = $this->qsend("estimate/$id");
        return $res->Estimate ?? null;
    }

    /**
     * Sync a Quote to a QBO Estimate
     * @param Quote $quote
     * @return mixed
     * @throws GuzzleException
     * @throws LogicException
     */
    public function byQuote(Quote $quote) : mixed
    {
        if ($quote->items()->count() == 0) return null;
        if ($quote->finance_quote_id)
        {
            $q = $this->find($quote->finance_quote_id);
        }
        else
        {
            $q = (object)[];
        }


        // Step 1 - Set basic estimate properties.
        $fqid = $quote->lead ? $quote->lead->finance_customer_id : $quote->account->finance_customer_id;
        $q->CustomerRef = (object)['value' => $fqid];
        $q->DueDate = $quote->expires_on->format("Y-m-d");
        $q->DocNumber = $quote->id;
        // Step 2 - Set Estimate Lines from Quote
        $lines = [];
        foreach ($quote->items as $item)
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
            $line->Amount = moneyFormat(bcmul($item->qty * $item->price, 1), false);
            // Finally add it to our array.
            $lines[] = $line;
        }
        $q->Line = $lines;
        // Step 3 - Submit to Quickbooks
        $res = $this->qsend("estimate", 'post', (array)$q);
        if (isset($res->Estimate) && isset($res->Estimate->Id))
        {
            $quote->update(['finance_quote_id' => $res->Estimate->Id]);
            $quote->refresh();
            return $res->Estimate;
        }
        return null; // Error perhaps?
    }

    /**
     * Removes an estimate from qbo.
     * @param string $id
     * @return void
     * @throws GuzzleException
     * @throws LogicException
     */
    public function delete(string $id): void
    {
        $qboEstimate = $this->find($id);
        if (!$qboEstimate) return;
        $this->qsend("estimate?operation=delete", 'post', [
            'SyncToken'   => $qboEstimate->SyncToken,
            'CustomerRef' => $qboEstimate->CustomerRef,
            'Id'          => $qboEstimate->Id
        ]);
    }


}
