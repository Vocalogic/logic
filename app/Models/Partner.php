<?php

namespace App\Models;

use App\Exceptions\LogicException;
use App\Operations\Admin\PartnerConnect;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Model;

/**
 * This is other logic partners to be used to assign leads to a partner
 * and be able to monitor quotes, etc.
 * @property mixed $commission_in_spiff
 * @property mixed $commission_in_mrc
 * @property mixed $commission_out_mrc
 * @property mixed $commission_out_spiff
 * @property mixed $partner_host
 * @property mixed $name
 * @property mixed $net_days
 */
class Partner extends Model
{
    protected $guarded = ['id'];
    protected $casts   = ['invited_on' => 'datetime', 'accepted_on' => 'datetime'];

    /**
     * Get Commission In Human Readable
     * @return string
     */
    public function getCommInAttribute(): string
    {
        if (!$this->commission_in_mrc && !$this->commission_in_spiff) return "N/A";
        if ($this->commission_in_mrc) return $this->commission_in_mrc . "% MRR";
        return $this->commission_in_spiff . " x MRR";
    }

    /**
     * Get Commission Out Human Readable
     * @return string
     */
    public function getCommOutAttribute(): string
    {
        if (!$this->commission_out_mrc && !$this->commission_out_spiff) return "N/A";
        if ($this->commission_out_mrc) return $this->commission_out_mrc . "% MRR";
        return $this->commission_out_spiff . " x MRR";
    }

    /**
     * When receiving an invite we need to notify the admins of this system that
     * a new invite has been received, and to check it.
     * @return void
     */
    public function notifyNewInvite(): void
    {
        template('system.invite', User::find(1), [$this]);
    }

    /**
     * Get a selectable list showing commissions for setting leads
     * @return array
     */
    static public function getSelectable(): array
    {
        $opts = [];
        $opts[''] = '-- Select Partner Assignment --';
        foreach (self::orderBy('name')->get() as $partner)
        {
            $opts[$partner->id] = sprintf("%s (%s)", $partner->name, $partner->commIn);
        }
        return $opts;
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
        // we need comm_out, net_days, and note.
        $p = new PartnerConnect($this);
        $p->submitPartnerInvitation($data);
    }

    /**
     * Accept partners invitation and send them the reciprocated commission structure.
     * @param array $data
     * @return void
     * @throws GuzzleException
     * @throws LogicException
     */
    public function submitPartnerAccept(array $data): void
    {
        $p = new PartnerConnect($this);
        $p->submitPartnerAccept($data);
    }

    /**
     * Attempt to Send the Partner a Lead
     * @param Lead $lead
     * @return void
     * @throws GuzzleException
     * @throws LogicException
     */
    public function submitLead(Lead $lead): void
    {
        $p = new PartnerConnect($this);
        $p->submitLead($lead);
    }

    /**
     * Submits lead activity to the partner, enabling two-way communication between partners.
     * @param Lead   $lead
     * @param string $message
     * @return void
     * @throws GuzzleException
     * @throws LogicException
     */
    public function submitLeadActivity(Lead $lead, string $message): void
    {
        $p = new PartnerConnect($this);
        $p->submitLeadActivity($lead, $message);
    }

    /**
     * Get Formatted Lead Update from our Partner
     * @param Lead $lead
     * @return object
     * @throws GuzzleException
     * @throws LogicException
     */
    public function getLeadUpdate(Lead $lead): object
    {
        $p = new PartnerConnect($this);
        return $p->getLeadUpdate($lead);
    }

    /**
     * When we have marked a lead that was sent to us as lost, we need to basically tell the
     * partner, the lead is yours to do with as you want again.
     * @param Lead $lead
     * @return void
     * @throws GuzzleException
     * @throws LogicException
     */
    public function disconnectLead(Lead $lead): void
    {
        $p = new PartnerConnect($this);
        $p->disconnectLead($lead);
    }

    /**
     * We will update the lead on the partner side, notify them it was sold
     * and close out the lead on their end. The rest will be handled via the
     * accounts api command.
     * @param Lead $lead
     * @return void
     * @throws GuzzleException
     */
    public function notifySoldLead(Lead $lead): void
    {
        try
        {
            $p = new PartnerConnect($this);
            $p->notifySoldLead($lead);
        } catch (Exception $e)
        {
            info("Could not inform partner of sold lead. (#$lead->id) - " . $e->getMessage());
            // We don't want to break our conversion process over a failed call.
        }
    }

    /**
     * Get list of accounts from a partner.
     * @return array
     * @throws GuzzleException
     */
    public function getAccounts(): array
    {
        try
        {
            $p = new PartnerConnect($this);
            return $p->getAccounts();
        } catch (Exception $e)
        {
            info("Could not retrieve accounts: " . $e->getMessage());
        }
        return [];
    }

    /**
     * Get Relateable Invoices from our Partner
     * @return array
     * @throws GuzzleException
     */
    public function getInvoices(): array
    {
        try
        {
            $p = new PartnerConnect($this);
            return $p->getInvoices();
        } catch (Exception $e)
        {
            info("Could not retrieve invoices: " . $e->getMessage());
        }
        return [];
    }

    /**
     * Check Commissions (run from monthly check)
     * @return void
     * @throws GuzzleException
     * @throws LogicException
     */
    public function checkCommissions(): void
    {
        $p = new PartnerConnect($this);
        $p->checkCommissions();
    }

    /**
     * Get a list of remote commissions
     * @return array
     * @throws GuzzleException
     */
    public function getRemoteCommissions(): array
    {
        try
        {
            $p = new PartnerConnect($this);
            return $p->getRemoteCommissions();
        } catch (Exception $e)
        {
            info("Could not retrieve invoices: " . $e->getMessage());
        }
        return [];
    }

    /**
     * Get remote Commission entry.
     * @param int $id
     * @return object
     * @throws GuzzleException
     */
    public function getRemoteCommission(int $id): object
    {
        try
        {
            $p = new PartnerConnect($this);
            return $p->getRemoteCommission($id);
        } catch (Exception $e)
        {
            info("Could not retrieve invoice: " . $e->getMessage());
        }
        return (object)[];
    }
}
