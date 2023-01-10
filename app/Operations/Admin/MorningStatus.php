<?php

namespace App\Operations\Admin;

/**
 * This class will build all of the required objects for the morning email going out.
 */
class MorningStatus
{
    /**
     * When we build this once, we will save the info here, so that our views can just call
     * a static helper and it not redo the entire operation over and over again.
     * @var array
     */
    static public array $status = [];

    /**
     * Our working object to return to the calling class.
     * @var array
     */
    public array $work = [];

    /**
     * Outside Caller for gathering data.
     * @return array
     */
    static public function status(): array
    {
        if (!empty(self::$status)) return self::$status;
        $x = new self;
        $x->init();
        self::$status = $x->work;
        return $x->work;
    }

    /**
     * This method will call all of the workers for building our email.
     * @return void
     */
    public function init() : void
    {
        $this->widgets();
        $this->staleLeads();
    }

    /**
     * Get Stale Leads and write to our array
     * @return void
     */
    private function staleLeads() : void
    {
        $this->work['staleLeads'] = [];
    }

    private function widgets() : void
    {
        $this->work['widgets'] = [];
        $this->work['widgets']['invoicedToday'] = WidgetGenerator::get('invoicedToday');
        $this->work['widgets']['outstandingBalance'] = WidgetGenerator::get('outstandingInvoices');
        $this->work['widgets']['mrr'] = WidgetGenerator::get('getMRR');

    }

}
