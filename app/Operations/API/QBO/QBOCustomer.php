<?php

namespace App\Operations\API\QBO;

use App\Enums\Core\EventType;
use App\Exceptions\LogicException;
use App\Models\Account;
use App\Models\Lead;
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
     * Delete a customer entry in quickbooks.
     * @param int $id
     * @return void
     * @throws GuzzleException
     * @throws LogicException
     */
    public function remove(int $id): void
    {
        $customer = $this->find($id);
        if (!$customer) return;
        $this->qsend("customer?operation=delete", 'post', [
            'SyncToken'   => $customer->SyncToken,
            'CustomerRef' => $customer->CustomerRef,
            'Id'          => $customer->Id
        ]);
    }

    /**
     * Lookup Account by name.
     * @param string $name
     * @return array
     * @throws Exception
     */
    public function findByName(string $name): array
    {
        $name = str_replace("'", "\'", $name);
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
     * @throws GuzzleException|LogicException
     */
    public function byAccount(Account $account): void
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
                'Line1'                  => $account->admin?->name,
                'Line2'                  => $account->name,
                'Line3'                  => $account->address,
                'Line4'                  => sprintf("%s, %s %s", $account->city, $account->state, $account->postcode),
                'Country'                => $account->country
            ],
            'ShipAddr'           => [
                'CountrySubDivisionCode' => $account->state,
                'City'                   => $account->city,
                'PostalCode'             => $account->postcode,
                'Line1'                  => $account->admin?->name,
                'Line2'                  => $account->name,
                'Line3'                  => $account->address,
                'Line4'                  => sprintf("%s, %s %s", $account->city, $account->state, $account->postcode),
                'Country'                => $account->country
            ],
            'Taxable'            => $account->taxable
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
            $data['Job'] = true;
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

    /**
     * Create a customer record with quickbooks using lead details.
     * This entry will be removed if the lead is lost.
     * @param Lead $lead
     * @return void
     * @throws GuzzleException
     * @throws LogicException
     */
    public function byLead(Lead $lead): void
    {
        if ($lead->finance_customer_id)
        {
            $c = $this->find($lead->finance_customer_id);
            $token = $c->SyncToken;
        }
        else
        {
            $exists = $this->findByName($lead->company);
            if (!empty($exists))
            {
                $lead->update(['finance_customer_id' => $exists[0]->Id]);
                return;
            }
        }
        $x = explode(" ", $lead->contact);
        $first = $x[0];
        $last = $x[1] ?? '';

        $data = [
            'FullyQualifiedName' => $lead->company,
            'PrimaryEmailAddr'   => [
                'Address' => $lead->email
            ],
            'DisplayName'        => $lead->company,
            'FamilyName'         => $first,
            'GivenName'          => $last,
            'PrimaryPhone'       => [
                'FreeFormNumber' => $lead->phone,
            ],
            'CompanyName'        => $lead->company,
            'BillAddr'           => [
                'CountrySubDivisionCode' => $lead->state,
                'City'                   => $lead->city,
                'PostalCode'             => $lead->zip,
                'Line1'                  => $lead->company,
                'Line2'                  => $lead->contact,
                'Line3'                  => $lead->address,
                'Line4'                  => sprintf("%s, %s %s", $lead->city, $lead->state, $lead->zip),
                'Country'                => 'US'
            ],
            'ShipAddr'           => [
                'CountrySubDivisionCode' => $lead->state,
                'City'                   => $lead->city,
                'PostalCode'             => $lead->zip,
                'Line1'                  => $lead->company,
                'Line2'                  => $lead->contact,
                'Line3'                  => $lead->address,
                'Line4'                  => sprintf("%s, %s %s", $lead->city, $lead->state, $lead->zip),
                'Country'                => 'US'
            ],
            'Taxable'            => $lead->taxable
        ];
        if (isset($token))
        {
            $data['SyncToken'] = $token;
            $data['Id'] = $lead->finance_customer_id;
        }
        // We need to check to see if this account has a parent, if so link it here.
        $res = $this->qsend("customer", 'post', $data);
        if (isset($res->Customer))
        {
            $lead->update(['finance_customer_id' => $res->Customer->Id]);
        }
        else
        {
            info("QBO Error Creating Customer From Lead");
        }
    }
}
