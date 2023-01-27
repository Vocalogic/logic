<?php

namespace App\Operations\API\QBO;

use App\Enums\Core\EventType;
use App\Models\Account;
use Exception;
use GuzzleHttp\Exception\GuzzleException;

class QBOCustomer extends QBOCore
{

    /**
     * Get a list of all customers.
     * @return mixed
     * @throws GuzzleException
     */
    public function all(): array
    {
        $res = $this->qsend("query", 'get', [
            'query' => 'SELECT * from Customer'
        ]);
        if ($res->QueryResponse && $res->QueryResponse->Customer)
        {
            return $res->QueryResponse->Customer;
        }
        else return [];
    }

    /**
     * Find account by ID
     * @param int $id
     * @return object|null
     * @throws Exception
     */
    public function find(int $id): ?object
    {
        $res = $this->qsend("query", 'get', [
            'query' => "SELECT * from Customer where Id = '$id'"
        ]);
        if ($res->QueryResponse && $res->QueryResponse->Customer && $res->QueryResponse->Customer[0])
        {
            return $res->QueryResponse->Customer[0];
        }
        else return null;
    }

    /**
     * Lookup Account by name.
     * @param string $name
     * @return array
     * @throws Exception
     */
    public function findByName(string $name): array
    {
        $res = $this->qsend("query", 'get', [
            'query' => "SELECT * from Customer where CompanyName LIKE '%$name%'"
        ]);
        if ($res->QueryResponse && isset($res->QueryResponse->Customer))
        {
            return $res->QueryResponse->Customer;
        }
        else return [];
    }


    /**
     * Create/Update a QBO Account
     * @param Account $account
     * @throws GuzzleException
     */
    public function byAccount(Account $account)
    {
        if ($account->finance_customer_id)
        {
            $c = $this->find($account->finance_customer_id);
            $token = $c->SyncToken;
        }
        else
        {
            $exists = $this->findByName($account->name);
            if (!empty($exists))
            {
                $account->update(['finance_customer_id' => $exists[0]->Id]);
                return;
            }
        }



        $data = [
            'FullyQualifiedName' => $account->name,
            'PrimaryEmailAddr'   => [
                'Address' => $account->admin->email
            ],
            'DisplayName'        => $account->name,
            'FamilyName'         => $account->admin->last,
            'GivenName'          => $account->admin->first,
            'PrimaryPhone'       => [
                'FreeFormNumber' => $account->admin->phone,
            ],
            'CompanyName'        => $account->name,
            'BillAddr'           => [
                'CountrySubDivisionCode' => $account->state,
                'City'                   => $account->city,
                'PostalCode'             => $account->postcode,
                'Line1'                  => $account->address,
                'Country'                => $account->country
            ],
            'Taxable'            => true
        ];
        if (isset($token))
        {
            $data['SyncToken'] = $token;
            $data['Id'] = $account->finance_customer_id;
        }
        // We need to check to see if this account has a parent, if so link it here.
        if ($account->parent && $account->parent->finance_customer_id) // Parent has to have sync first.
        {
            $data['ParentRef'] = [
                'value' => $account->parent->finance_customer_id
            ];
            $data['Job'] = false;
        }

        $res = $this->qsend("customer", 'post', $data);
        if (isset($res->Customer))
        {
            $account->update(['finance_customer_id' => $res->Customer->Id]);
        }
        else
        {
            info("QBO Error");
        }
    }


}
