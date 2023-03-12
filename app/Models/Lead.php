<?php

namespace App\Models;

use App\Enums\Core\ACL;
use App\Traits\HasLogTrait;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use LogicException;

/**
 * @property mixed $rating
 * @property mixed $id
 * @property mixed $company
 * @property mixed $quotes
 * @property mixed $hasPreferred
 * @property mixed $contact
 * @property mixed $agent
 * @property mixed $email
 * @property mixed $address
 * @property mixed $address2
 * @property mixed $city
 * @property mixed $state
 * @property mixed $zip
 * @property mixed $phone
 * @property mixed $logo_id
 * @property mixed $updated_at
 * @property mixed $hash
 * @property mixed $partner
 * @property mixed $partner_sourced
 * @property mixed $status
 * @property mixed $finance_customer_id
 * @property mixed $taxable
 * @property mixed $created_at
 */
class Lead extends Model
{
    use HasLogTrait;

    protected    $guarded = ['id'];
    public       $casts   = ['forecast_date' => 'datetime'];
    public array $tracked = [
        'company'            => "Company Name",
        'contact'            => "Primary Contact",
        'email'              => "Email Address",
        'phone'              => "Contact Phone",
        'title'              => "Contact Title",
        'active'             => "Active State",
        'agent_id'           => "Lead Agent|agent.name",
        'description'        => "Lead Description",
        'rating'             => "Lead Rating",
        'address'            => "Address",
        'address2'           => "Address Line 2",
        'city'               => "City",
        'state'              => "State",
        'zip'                => "Zip Code",
        'forecast_date'      => "Forecasted Close Date",
        'forecast_note'      => "Forecasted Note",
        'lead_type_id'       => "Lead Type|type.name",
        'lost_on'            => "Date Lead Lost",
        'reactivate_on'      => "Date for Reactivation",
        'reason'             => "Lost Lead Reason",
        'lead_origin_id'     => "Lead Origin|origin.name",
        'lead_origin_detail' => "Lead Origin Detail",
        'discovery'          => "Discovery Information",
        'lead_status_id'     => "Lead Status|status.name",
        'taxable'            => "Taxable State",
    ];

    /**
     * A lead belongs to an agent.
     * @return BelongsTo
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /**
     * Associated lead type.
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(LeadType::class, 'lead_type_id');
    }

    /**
     * A lead has many discovery entries.
     * @return HasMany
     */
    public function discoveries(): HasMany
    {
        return $this->hasMany(LeadDiscovery::class);
    }

    /**
     * Leads can be given to partners.
     * @return BelongsTo
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * A lead has a status
     * @return BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(LeadStatus::class, 'lead_status_id');
    }

    /**
     * Get answer to discovery question
     * @param Discovery $discovery
     * @return string|null
     */
    public function getDiscoveryAnswer(Discovery $discovery): ?string
    {
        $a = $this->discoveries()->where('discovery_id', $discovery->id)->first();
        return $a?->value;
    }

    /**
     * A lead has many activities
     * @return HasMany
     */
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'refid')->where('type', 'LEAD');
    }

    /**
     * A lead has many quotes
     * @return HasMany
     */
    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    /**
     * A lead can have many projects.
     * @return HasMany
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     * A lead can have an origin.
     * @return BelongsTo
     */
    public function origin(): BelongsTo
    {
        return $this->belongsTo(LeadOrigin::class, 'lead_origin_id');
    }

    /**
     * Get the age of the lead in days.
     * @return int
     */
    public function getAgeAttribute(): int
    {
        return $this->created_at->diffInDays();
    }

    /**
     * Get rating definitions
     * @return string
     */
    public function getRatingHumanAttribute(): string
    {
        return match ($this->rating)
        {
            0 => 'Not Set',
            1 => 'Very Low',
            2 => 'Low',
            3 => 'Maybe',
            4 => 'Very Good',
            5 => 'Ready to Go',
            default => 0
        };
    }

    /**
     * Get contacts first name
     * @return string
     */
    public function getFirstAttribute(): string
    {
        $x = explode(" ", $this->contact);
        return ucfirst(strtolower($x[0]));
    }

    /**
     * Get contacts last name.
     * @return string
     */
    public function getLastAttribute(): string
    {
        $x = explode(" ", $this->contact);
        $last = end($x);
        return ucfirst(strtolower($last));
    }

    /**
     * Get MRR off of preferred quote
     * @return float
     */
    public function getPrimaryMrrAttribute(): float
    {
        if (!$this->hasPreferred) return 0.00;
        $quote = $this->quotes->where('preferred', true)->first();
        return $quote->mrr;
    }

    /**
     * Get NRC off of preferred quote.
     * @return float
     */
    public function getPrimaryNrcAttribute(): float
    {
        if (!$this->hasPreferred) return 0.00;
        $quote = $this->quotes->where('preferred', true)->first();
        return $quote->nrc;
    }

    /**
     * Determine if this lead has a preferred quote or not.
     * @return bool
     */
    public function getHasPreferredAttribute(): bool
    {
        return (bool)$this->quotes->where('preferred', true)->first();
    }

    /**
     *
     * @return string
     */
    public function getDiscoveryLinkAttribute(): string
    {
        return sprintf("%s/shop/presales/%s", setting('brand.url'), $this->hash);
    }

    /**
     * Send Discovery Request
     * @return void
     */
    public function sendDiscovery(): void
    {
        template("lead.discovery", null, [$this, $this->agent], [], $this->email, $this->contact);
    }

    /**
     * Create an account and the admin user for the account.
     * @return Account
     */
    public function createAccount(): Account
    {
        $account = (new Account)->create([
            'name'           => $this->company,
            'address'        => $this->address,
            'address2'       => $this->address2,
            'city'           => $this->city,
            'state'          => $this->state,
            'postcode'       => $this->zip,
            'country'        => 'US',
            'phone'          => $this->phone,
            'active'         => 1,
            'agent_id'       => $this->agent->id,
            'logo_id'        => $this->logo_id,
            'next_bill'      => now(),
            'bills_on'       => now()->day,
            'payment_method' => setting('invoices.default')
        ]);
        $user = (new User)->create([
            'name'       => $this->contact,
            'email'      => $this->email,
            'password'   => '',
            'account_id' => $account->id,
            'acl'        => ACL::ADMIN,
            'active'     => 1,
            'is_agent'   => 0
        ]);
        $user->authorizeIp();
        $account->fresh();
        return $account;
    }

    /**
     * Does this lead require an update?
     * @return bool
     */
    public function getRequiresUpdateAttribute(): bool
    {
        $setting = setting('leads.aging');
        if (!$setting) return false;
        if ($this->status && $this->status->disable_warnings) return false;
        $diff = $this->updated_at->diffInDays();
        return $diff > (int)$setting;
    }

    /**
     * Name is used by activities, so we'll just link that to company
     * @return string
     */
    public function getNameAttribute(): string
    {
        return $this->company;
    }

    /**
     * Get the last comment from activities for this lead
     * @return string
     */
    public function getLastCommentAttribute(): string
    {
        $act = $this->activities()->where('system', 0)->orderBy('created_at', 'DESC')->first();
        if (!$act) return "No Activity";
        return sprintf("%s - %s on %s", $act->post, $act->user?->name, $act->created_at->format("m/d h:ia"));
    }

    /**
     * Get alternate quotes for copy.
     * @param Quote $quote
     * @return array
     */
    public function alternateQuotes(Quote $quote): array
    {
        $data = [];
        foreach ($this->quotes()->where('id', '!=', $quote->id)->get() as $q)
        {
            $data[$q->id] = sprintf("%s (#%d)", $q->name, $q->id);
        }
        return $data;
    }

    /**
     * Submit the lead to a partner.
     * @param Partner $partner
     * @return void
     * @throws \App\Exceptions\LogicException
     * @throws GuzzleException
     */
    public function submitToPartner(Partner $partner): void
    {
        $partner->submitLead($this);
        $this->update(['partner_id' => $partner->id]);
    }

    /**
     * Set new status for a lead and perform any actions required by the status.
     * @param int $status
     * @return void
     */
    public function setStatus(int $status): void
    {
        $this->update(['lead_status_id' => $status]);
    }

    /**
     * Get how much is commissionable on this lead
     * @return float
     */
    public function getCommissionableAmountAttribute(): float
    {
        $quote = $this->quotes()->where('preferred', 1)->first();
        if (!$quote) return 0;
        return $quote->commissionable;
    }

}
