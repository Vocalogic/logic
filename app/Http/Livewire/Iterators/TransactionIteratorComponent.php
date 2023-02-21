<?php

namespace App\Http\Livewire\Iterators;

use App\Http\Livewire\Admin\LwTableComponent;
use App\Models\Transaction;

class TransactionIteratorComponent extends LwTableComponent
{
    /**
     * Define text array headers for our table.
     * @var array
     */
    public array $headers = [
        'ID'      => ['id'],
        'Date'    => ['created_at'],
        'Invoice' => ['invoice_id', 'invoice', 'id'],
        'Account' => ['account_id', 'account', 'name'],
        'Amount'  => ['amount'],
        'Fee'     => ['fee'],
        'Net'     => ['net|computed'],
        'Type'    => ['method']
    ];

    /**
     * Define how to render a single row entry in html.
     * @var string
     */
    public string $entity = 'admin.transactions.entity';

    /**
     * Define the type of model we are using.
     */
    public string $model = Transaction::class;

    /**
     * Eager load all the relationships we will need for this table.
     * @param $collection
     * @return mixed
     */
    public function preFilters($collection): mixed
    {
        return $collection->with([
            'invoice',
            'account.invoices',
            'account.invoices.items',
            'account.invoices.transactions',
            'invoice.items',
            'account',
            'account.items',
            'account.items.addons'
        ]);
    }

}
