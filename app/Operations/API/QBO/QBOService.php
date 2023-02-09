<?php

namespace App\Operations\API\QBO;

use App\Exceptions\LogicException;
use App\Models\BillItem;
use App\Models\Integration;
use GuzzleHttp\Exception\GuzzleException;

class QBOService extends QBOCore
{
    /**
     * Get a list of all services
     * @return mixed
     * @throws GuzzleException
     */
    public function all(): mixed
    {
        return $this->query("Item", "Type", "Service", false);
    }

    /**
     * Find Service by Id
     * @param int $id
     * @return mixed
     * @throws GuzzleException
     */
    public function find(int $id): mixed
    {
        return $this->query("Item", "Id", $id);
    }


    /**
     * Create or sync a service by Logic Service.
     * This will not return anything but set it directly.
     * @param BillItem $item
     * @return void
     * @throws GuzzleException
     * @throws LogicException
     */
    public function byItem(BillItem $item): void
    {
        $desc = $item->description;
        $price = $item->nrc ?: $item->mrc;
        // First if we already have a service mapping, we are trying to update
        $data = (object)[];
        if ($item->finance_item_id)
        {
            $s = $this->find($item->finance_item_id);
            $data = $s;
        }
        else // No token found
        {
            $id = $this->findBySku($item->code); // Lookup the id by Code.. if found update and keep going.
            if ($id)
            {
                $item->update(['finance_item_id' => $id]);
                $item->refresh();
                $s = $this->find($item->finance_item_id);
                $data = $s;
            }
        }
        // Build Service Object

        $data->SubItem = true;
        $data->Type = "Service";
        $data->Name = $item->name;
        $data->Sku = $item->code;
        $data->Description = strip_tags($desc);
        $data->UnitPrice = moneyFormat($price, false);
        $data->Taxable = $item->taxable ? 'true' : 'false';
        $data->ParentRef = (object)[
            'value' => $item->category->finance_category_id
        ];

        $data->IncomeAccountRef = (object)[
            'value' => $this->getIncomeAccount()
        ];


        // Let's set purchase cost if we have an expense line item for monthly opex.
        if ($item->ex_opex || $item->ex_capex)
        {
            $data->PurchaseCost = $item->ex_opex
                ? moneyFormat($item->ex_opex, false)
                : moneyFormat($item->ex_capex, false); // could be service or product.
        }

        $res = $this->qsend("item?minorversion=4", 'post', (array)$data);
        if (isset($res->Item) && isset($res->Item->Id))
        {
            $item->update(['finance_item_id' => $res->Item->Id]);
        }
    }


    /**
     * Attempt to find a service by its name to prevent duplicates.
     * @param mixed $name
     * @return int
     * @throws GuzzleException|LogicException
     */
    public function findByName(mixed $name): int
    {
        $res = $this->qsend("query", 'get', [
            'query' => "SELECT * from Item where Name = '$name' and Type='Service'"
        ]);
        if (isset($res->QueryResponse->Item))
        {
            return (int)$res->QueryResponse->Item[0]->Id;
        }
        return 0;
    }

    /**
     * Attempt to find a service by its code to prevent duplicates.
     * @param mixed $code
     * @return int
     * @throws GuzzleException
     */
    public function findBySku(mixed $code): int
    {
        $res = $this->qsend("query", 'get', [
            'query' => "SELECT * from Item where Sku = '$code' and Type='Service'"
        ]);
        if (isset($res->QueryResponse->Item))
        {
            return (int)$res->QueryResponse->Item[0]->Id;
        }
        return 0;
    }


    /**
     * Get Income account from config or pull it from qb.
     * @return string
     * @throws GuzzleException
     */
    private function getIncomeAccount(): string
    {
        $aid = '';
        $i = Integration::where('ident', 'qbo')->first();
        $data = $i->unpacked;
        if (isset($data->qbo_default_income) && $data->qbo_default_income)
        {
            return $data->qbo_default_income;
        }
        // Not found get from qboaccount
        $a = new QBOAccount($this->config);
        $result = $a->findByName("Sales"); // Try to find default sales account.
        if (isset($result->QueryResponse->Account))
        {
            $first = $result->QueryResponse->Account[0];
            $aid = $first->Id;
            $data->qbo_default_income = $aid;
            $i->update(['data' => $data]);
        }
        return $aid;
    }

}
