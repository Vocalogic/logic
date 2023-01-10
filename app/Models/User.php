<?php

namespace App\Models;

use App\Enums\Core\ACL;
use App\Enums\Core\EventType;
use App\Operations\Core\Gravatar;
use Firebase\JWT\JWT;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property mixed $account_id
 * @property mixed $support_user_id
 * @property mixed $email
 * @property mixed $account
 * @property mixed $name
 * @property mixed $preferences
 * @property mixed $active
 * @property mixed $hash
 * @property mixed $id
 * @property mixed $tfa
 * @property mixed $phone
 * @property mixed $goal_self_monthly
 * @property mixed $goal_self_quarterly
 * @property mixed $goal_monthly
 * @property mixed $goal_quarterly
 * @property mixed $goal_f_monthly
 * @property mixed $goal_f_quarterly
 * @property mixed $acl
 * @property mixed $agent_comm_mrc
 * @property mixed $agent_comm_spiff
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = ['id'];
    protected $casts   = [
        'email_verified_at' => 'datetime',
        'preferences'       => 'json',
        'acl'               => ACL::class
    ];

    /**
     * Disable 2FA Globally for Dusk Tests
     * @var bool
     */
    static public bool $disableTFA = false;

    /**
     * A user belongs to an account.
     * @return BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * A user has a single TFA Record.
     * @return HasMany
     */
    public function tfa(): HasMany
    {
        return $this->hasMany(UserTfa::class, 'user_id');
    }

    /**
     * Generate a unique hash for new accounts.
     * @return void
     */
    public function generateHash(): void
    {
        $this->update(['hash' => uniqid("VERIFY-")]);
    }

    /**
     * Get email verification link
     * @return string
     */
    public function getVerifyAttribute(): string
    {
        return sprintf("%s/verify/%s", setting('brand.url'), $this->hash);
    }

    /**
     * If you are account 1 then you are an admin.
     * @return bool
     */
    public function getIsAdminAttribute(): bool
    {
        return $this->account_id == 1 && $this->acl->value === ACL::ADMIN->value;
    }

    public function getIsSalesAttribute(): bool
    {
        return  $this->acl->value === ACL::SALES->value;
    }

    /*
    * Return first dot last name like c.horne
    */
    public function getShortAttribute(): string
    {
        $x = explode(" ", $this->name);
        return count($x) > 1 ? strtolower(trim($x[0][0]) . "." . trim($x[1])) : $this->first;
    }

    /*
     * Return first name
     */
    public function getFirstAttribute(): string
    {
        $x = explode(" ", $this->name);
        return ucfirst(strtolower($x[0]));
    }

    /*
    * Return last name
    */
    public function getLastAttribute(): string
    {
        $x = explode(" ", $this->name);
        $last = end($x);
        return ucfirst(strtolower($last));
    }

    /**
     * Does this user require TFA Verification
     * @return bool
     */
    public function needsVerification(): bool
    {
        if (env('APP_ENV') == 'local') return false; // Remove if need to test 2fa
        if (self::$disableTFA) return false;
        if (setting('account.2fa_method') == 'SMS' && !user()->phone) return false;
        $ip = app('request')->ip();
        $entry = $this->tfa()->where('ip', $ip)->first();
        if (!$entry) return true;
        $days = (int)setting('account.2fa_days') ?: 0;
        if (!$entry->last_verification) return true;
        $sinceLastSuccess = now()->diffInDays($entry->last_verification);
        if ($sinceLastSuccess > $days) return true;
        return false;
    }

    /**
     * Generate new code and create TFA Record if it does not exist.
     * @return void
     */
    public function generateTFA(): void
    {
        $ip = app('request')->ip();
        $e = $this->tfa()->where('ip', $ip)->first();
        if (!$e)
        {
            $this->tfa()->create([
                'last_sent' => now(),
                'ip'        => app('request')->ip(),
            ]);
        }
    }

    /**
     * Authorize current IP
     * @return void
     */
    public function authorizeIp(): void
    {
        $ip = app('request')->ip();
        $e = $this->tfa()->where('ip', $ip)->first();
        if (!$e)
        {
            $e = $this->tfa()->create([
                'last_sent' => now(),
                'ip'        => app('request')->ip(),
            ]);
        }
        $e->update(['last_verification' => now()]);
    }

    /**
     * Get Gravatar Image
     * @return string
     */
    public function getAvatarAttribute(): string
    {
        return Gravatar::get($this->email);
    }

    /**
     * A user has many notifications
     * @return HasMany
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(LOLog::class, 'user_id')->where('type', EventType::SEV_NOTIFY);
    }

    /**
     * Get the number of unread notifications.
     * @return int
     */
    public function getUnreadAttribute(): int
    {
        return $this->notifications()->where('read', false)->count();
    }

    /**
     * Get email formatted link
     * @return string
     */
    public function getForgotLinkAttribute(): string
    {
        return sprintf("%s/forgot/%s", setting('brand.url'), $this->hash);
    }

    /**
     * Users can individually have many commissions
     * @return HasMany
     */
    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class);
    }

    /**
     * Send Forgot Password
     * @return void
     */
    public function sendForgotPassword(): void
    {
        $this->update(['hash' => uniqid('PW-')]);
        $this->refresh();
        template('user.forgot', $this, [$this]);
    }

    /**
     * Set preference for a user
     * @param string      $key
     * @param string|null $value
     * @return string|null
     */
    public function preference(string $key, ?string $value = null): ?string
    {
        $prefs = (object)$this->preferences;
        if (!$value)
        {
            if (!isset($prefs->{$key})) return null;
            return $prefs->{$key};
        }
        else
        {
            $prefs->{$key} = $value;
            $this->update(['preferences' => $prefs]);
            return null;
        }
    }

    /**
     * Email a verification request.
     * @return void
     */
    public function requestVerification(): void
    {
        $this->generateHash();
        $this->refresh();
        template('account.verify', $this, [$this]);
    }



    /**
     * Get a list of agents to assign for accounts.
     * @return array
     */
    static public function getAgentsSelectable(): array
    {
        $agents = [];
        $agents[0] = 'No Agent';
        foreach (self::where('account_id', 1)->get() as $user)
        {
            $agents[$user->id] = $user->name . " (" . $user->account?->name.")";
        }
        foreach(self::where('is_agent', true)->get() as $user)
        {
            $agents[$user->id] = $user->name . " (" . $user->account?->name.")";
        }
        return $agents;
    }

    /**
     * Get number of leads for agent logged in.
     * @return int
     */
    public function getActiveLeadsAttribute(): int
    {
        return Lead::where('agent_id', user()->id)->where('active', true)->count();
    }

    /**
     * Get number of active accounts for agent.
     * @return int
     */
    public function getActiveAccountsAttribute(): int
    {
        return Account::where('agent_id', user()->id)->where('active', true)->count();
    }

    /**
     * Get agent's total commissions expected monthly.
     * @return float
     */
    public function getTotalCommissionAttribute(): float
    {
        $total = 0;
        foreach (Account::where('active', true)->where('agent_id', $this->id)->get() as $account)
        {
            $total += $account->commissionable;
        }
        return $total;
    }

}
