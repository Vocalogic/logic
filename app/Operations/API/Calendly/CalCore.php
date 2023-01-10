<?php

namespace App\Operations\API\Calendly;

use App\Enums\Core\MeetingType;
use App\Exceptions\LogicException;
use App\Models\Integration;
use App\Operations\API\APICore;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;

class CalCore extends APICore
{
    public string      $pat;
    public object      $unpacked;
    public string      $base = "https://api.calendly.com/";
    public Integration $calendlyIntegration;

    /**
     * Initialize Calendly API with Personal Access Token (PAT)
     */
    public function __construct()
    {
        $i = Integration::where('ident', 'calendly')->first();
        $this->calendlyIntegration = $i;
        $unpacked = $i->unpacked;
        $this->pat = $unpacked->calendly_pat;
        $this->unpacked = $unpacked;
        $this->calHeaders();
        parent::__construct();
        $this->checkRequired();
    }

    /**
     * Save our UID and Org Ident
     * @return void
     * @throws GuzzleException
     * @throws LogicException
     */
    public function checkRequired(): void
    {
        if (!$this->unpacked->calendly_uid)
        {
            $me = $this->getMe();
            if (isset($me->uri) && isset($me->current_organization))
            {
                $this->calendlyIntegration->setRequirement('calendly_uid', $me->uri);
                $this->calendlyIntegration->setRequirement('calendly_oid', $me->current_organization);
            }
        }
    }

    /**
     * Set headers for proper calls.
     * @return void
     */
    public function calHeaders(): void
    {
        $this->setHeaders([
            'Authorization' => 'Bearer ' . $this->pat,
            'Content-Type'  => 'application/json'
        ]);
    }

    /**
     * Get scheduled events for X days out, default 7.
     * @param int $daysOut
     * @return mixed
     * @throws LogicException
     * @throws GuzzleException
     */
    public function getScheduledEvents(int $daysOut = 7) : mixed
    {
        $opts = ['organization' => $this->unpacked->calendly_oid];
        return $this->send($this->base . "scheduled_events", 'get', $opts);
    }

    /**
     * Get event by UUID (not full url)
     * @param string $uid
     * @return mixed
     * @throws GuzzleException
     * @throws LogicException
     */
    public function getEvent(string $uid)
    {
        $result = $this->send($this->base . "scheduled_events/$uid");
        return $result;
    }

    /**
     * Try to pair up a Logic meeting event type with a calendly one.
     * @param string $calendlyType
     * @return MeetingType
     */
    public function resolveType(string $calendlyType): MeetingType
    {
        return match ($calendlyType)
        {
            'custom' => MeetingType::CustomerLocation,
            'outbound_call' => MeetingType::PhoneCall,
            'google' => MeetingType::WebConference,
            default => MeetingType::defaultType()
        };
    }


    /**
     * Get our organization and our uid
     * @return object
     * @throws GuzzleException
     * @throws LogicException
     */
    public function getMe(): object
    {
        $result = $this->send($this->base . "users/me");
        return $result->resource;
    }

    /**
     * Sync all events down and create them if they have not been created.
     * @return void
     */
    public function syncDown() : void
    {
        // TODO: This
    }

}
