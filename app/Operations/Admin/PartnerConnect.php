<?php

namespace App\Operations\Admin;

use App\Exceptions\LogicException;
use App\Models\Lead;
use App\Models\Partner;
use App\Operations\API\APICore;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;

class PartnerConnect extends APICore
{
    public Partner $partner;
    public string  $base;
    public string  $myCode;

    /**
     * Establish a relationship to our partner.
     * @param Partner $partner
     * @throws LogicException
     */
    public function __construct(Partner $partner)
    {
        parent::__construct();
        $this->partner = $partner;
        $this->base = sprintf("https://%s/api/", $this->partner->partner_host);
        $this->myCode = license()->partner_code;
    }

    /**
     * This command will check for any due commissions. This will see if the invoice has been
     * paid and the diff is >= net days agreed upon
     * @return void
     * @throws GuzzleException
     * @throws LogicException
     */
    public function checkCommissions(): void
    {
        $response = $this->send($this->base . "partners/$this->myCode/invoices");
        if (!$response->success)
        {
            throw new LogicException($response->message);
        }
        $invoices = $response->invoices ?: [];
        $payableInvoices = [];
        // array of partner invoices that are assigned to us.
        foreach ($invoices as $invoice)
        {
            if ($invoice->commissioned) continue; // Invoice already commissioned. skip

            // We haven't been paid on this yet.
            if (!$invoice->paid_on)
            {
                info("Partner Invoice #$invoice->number has not been paid. Skipping.");
                continue;
            }

            // Invoice has been paid.. is it within our net days?
            $paidOn = Carbon::createFromTimestamp($invoice->paid_on);
            $net = $this->partner->net_days;
            $daysSincePaid = now()->diffInDays($paidOn); // ex. 45 days since paid.
            if ($daysSincePaid >= $net)
            {
                $payableInvoices[] = $invoice;
            }
        } // fe invoice

        if (sizeOf($payableInvoices))
        {
            // We need to submit this invoice to partner, and collect on it automatically if we can.
            $this->requestCommissionPayment($payableInvoices);
        }

    }

    /**
     * Sends a request to the partner to join this company in exchanging leads
     * @param array $data
     * @return void
     * @throws LogicException
     * @throws GuzzleException
     */
    public function submitPartnerInvitation(array $data): void
    {
        $response = $this->send($this->base . "partners/invite", 'post', $data);
        if (!$response->success)
        {
            throw new LogicException($response->message);
        }
        $this->partner->update(['invited_on' => now()]);
    }

    /**
     * Accept Partner Invitation
     * @param array $data
     * @return void
     * @throws GuzzleException
     * @throws LogicException
     */
    public function submitPartnerAccept(array $data): void
    {
        $response = $this->send($this->base . "partners/accept", 'post', $data);
        if (!$response->success)
        {
            throw new LogicException($response->message);
        }
        $this->partner->update(['accepted_on' => now(), 'status' => "Accepted"]);
    }

    /**
     * Submit lead to partner.
     * @param Lead $lead
     * @return void
     * @throws GuzzleException
     * @throws LogicException
     */
    public function submitLead(Lead $lead): void
    {
        $data = ['lead' => $lead];
        $response = $this->send($this->base . "partners/$this->myCode/leads", 'post', $data);
        if (!$response->success)
        {
            throw new LogicException($response->message);
        }
    }

    /**
     * Submit Lead Activity to Partner
     * @param Lead   $lead
     * @param string $message
     * @return void
     * @throws GuzzleException
     * @throws LogicException
     */
    public function submitLeadActivity(Lead $lead, string $message): void
    {
        $data = ['message' => $message];
        $response = $this->send($this->base . "partners/$this->myCode/leads/$lead->hash/activity", 'post', $data);
        if (!$response->success)
        {
            throw new LogicException($response->message);
        }
    }

    /**
     * Get Lead Status based on a local lead hash
     * @param Lead $lead
     * @return object
     * @throws GuzzleException
     * @throws LogicException
     */
    public function getLeadUpdate(Lead $lead): object
    {
        $response = $this->send($this->base . "partners/$this->myCode/leads/$lead->hash");
        if (!$response->success)
        {
            throw new LogicException($response->message);
        }
        return $response->lead;
    }

    /**
     * Inform Partner we have marked our lead as lost giving them control back to the lead.
     * @param Lead $lead
     * @return void
     * @throws GuzzleException
     * @throws LogicException
     */
    public function disconnectLead(Lead $lead): void
    {
        $response = $this->send($this->base . "partners/$this->myCode/leads/$lead->hash/disconnect");
        if (!$response->success)
        {
            throw new LogicException($response->message);
        }
    }

    /**
     * Inform partner to close their lead, but still keep it locked because we sold it.
     * @param Lead $lead
     * @return void
     * @throws GuzzleException
     * @throws LogicException
     */
    public function notifySoldLead(Lead $lead): void
    {
        $response = $this->send($this->base . "partners/$this->myCode/leads/$lead->hash/sold");
        if (!$response->success)
        {
            throw new LogicException($response->message);
        }
    }

    /**
     * Get a list of accounts that are assigned to us. Gets minimal data on next bill date
     * and mrr etc.
     * @return array
     * @throws GuzzleException
     * @throws LogicException
     */
    public function getAccounts(): array
    {
        $response = $this->send($this->base . "partners/$this->myCode/accounts");
        if (!$response->success)
        {
            throw new LogicException($response->message);
        }
        return $response->accounts;
    }

    /**
     * Get all invoices from our partner that relate to us.
     * @return array
     * @throws GuzzleException
     * @throws LogicException
     */
    public function getInvoices(): array
    {
        $response = $this->send($this->base . "partners/$this->myCode/invoices");
        if (!$response->success)
        {
            throw new LogicException($response->message);
        }
        return $response->invoices;
    }

    /**
     * Takes an array of invoices from the partner that are payable and
     * generates an invoice and submits it to the partner.
     * @param array $payableInvoices
     * @return void
     * @throws GuzzleException
     * @throws LogicException
     */
    private function requestCommissionPayment(array $payableInvoices): void
    {
        // We are going to send a request for payment by generating a partner invoice
        // with the requested invoices. We will use the ids found in the array of
        // payable invoices.
        $iids = [];
        foreach ($payableInvoices as $inv)
        {
            $iids[] = $inv->number;
        }
        $data = ['ids' => $iids];
        $response = $this->send($this->base . "partners/$this->myCode/commission/request", 'post', $data);
        if (!$response->success)
        {
            throw new LogicException($response->message);
        }
    }

    /**
     * Get a list of commissions that are either due to us or been paid to us.
     * @return array
     * @throws GuzzleException
     * @throws LogicException
     */
    public function getRemoteCommissions() : array
    {
        $response = $this->send($this->base . "partners/$this->myCode/commissions");
        if (!$response->success)
        {
            throw new LogicException($response->message);
        }
        return $response->commissions;
    }

    /**
     * Get a single remote commission invoice.
     * @param int $id
     * @return object
     * @throws GuzzleException
     * @throws LogicException
     */
    public function getRemoteCommission(int $id) : object
    {
        $response = $this->send($this->base . "partners/$this->myCode/commissions/$id");
        if (!$response->success)
        {
            throw new LogicException($response->message);
        }
        return $response->commission;
    }


}
