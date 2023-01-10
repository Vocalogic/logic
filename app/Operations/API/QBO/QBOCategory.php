<?php

namespace App\Operations\API\QBO;

use App\Models\BillCategory;
use GuzzleHttp\Exception\GuzzleException;

class QBOCategory extends QBOCore
{
    /**
     * Return a list of all service categories currently in QB.
     * @return mixed
     * @throws GuzzleException
     */
    public function all() : mixed
    {
        return $this->query("Item", 'Type', 'Category', false);
    }

    /**
     * Create or sync a service category by Logic Category.
     * This will not return anything but set it directly.
     * @param BillCategory $category
     * @return void
     * @throws GuzzleException
     */
    public function byCategory(BillCategory $category): void
    {
        $data = (object) [];
        if (!$category->finance_category_id)
        {
            $exist = $this->findByName($category->name);
            if ($exist) $data = $exist;
        }
        else
        {
            $data = $this->find($category->finance_category_id);
        }

        // Object Construction
        $data->SubItem = false;
        $data->Type = 'Category';
        $data->Name = $category->name;

        $res = $this->qsend("item?minorversion=4", 'post', (array) $data);
        if (isset($res->Item) && isset($res->Item->Id))
        {
            $category->update(['finance_category_id' => $res->Item->Id]);
        }
    }

    /**
     * Attempt to find a category by its name to prevent duplicates.
     * @param mixed $name
     * @return mixed
     * @throws GuzzleException
     */
    public function findByName(mixed $name)  : mixed
    {
        return $this->query("Item", "Name", $name);
    }

    /**
     * Find Category by id
     * @param int $id
     * @return mixed
     * @throws GuzzleException
     */
    public function find(int $id): mixed
    {
        $res = $this->qsend("query", 'get', [
            'query' => "SELECT * from Item where Id='$id'"
        ]);
        if (isset($res->QueryResponse->Item))
        {
            return $res->QueryResponse->Item[0];
        }
        return null;
    }

}
