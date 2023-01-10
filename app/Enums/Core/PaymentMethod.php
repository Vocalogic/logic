<?php

namespace App\Enums\Core;

use App\Models\Account;

/**
 * Define payment methods that can be used for payments.
 */
enum PaymentMethod: string
{
    case AccountCredit = "Account Credit";
    case CreditCard = "Credit Card";
    case PaperCheck = "Check";
    case EFT = "EFT";
    case PayPal = "PayPal";
    case Cash = "Cash";
    case ACH = "ACH";

    /**
     * Return a human-readable description of this payment method.
     * @return string
     */
    public function getDescription(): string
    {
        return match ($this)
        {
            PaymentMethod::AccountCredit => "Applied from Account Credit",
            PaymentMethod::CreditCard => "Paid via Credit Card",
            PaymentMethod::PaperCheck => "Paid via Check",
            PaymentMethod::EFT => "Paid via Electronic Funds Transfer",
            PaymentMethod::PayPal => "Paid via PayPal",
            PaymentMethod::Cash => "Paid via Cash",
            PaymentMethod::ACH => "Paid via ACH Debit"
        };
    }

    /**
     * Return a description of a credit card or other form of payment that may have
     * additional verbiage about it.
     * @param Account $account
     * @return string|null
     */
    public function getAdditionalDetails(Account $account): ?string
    {
        return match ($this)
        {
            self::CreditCard => sprintf("ending in x%d", $account->merchant_payment_last4),
            self::AccountCredit => sprintf(" (Balance: $%0.2f)", $account->account_credit),
            self::ACH => sprintf("ending in x%d", substr($account->merchant_ach_account, -4)),
            default => null,
        };
    }

    /**
     * Can this payment method be auto processed?
     * @return bool
     */
    public function canAutoBill(): bool
    {
        return match ($this)
        {
            self::CreditCard, self::ACH => true,
            default => false
        };
    }

    /**
     * What payment methods can the user update themselves?
     * @return bool
     */
    public function canSelfUpdate(): bool
    {
        return match ($this)
        {
            self::ACH => false,
            default => true
        };
    }


    /**
     * Get a selectable array for views.
     * @return array
     */
    static public function selectable(): array
    {
        $opts = [];
        $opts[''] = '-- Select Method --';
        foreach (self::cases() as $case)
        {
            $opts[$case->value] = $case->value;
        }
        return $opts;
    }

    /**
     * Can an account use this payment method?
     * @param Account $account
     * @return bool
     */
    public function canUse(Account $account): bool
    {
        return match ($this)
        {
            PaymentMethod::CreditCard => (bool)$account->merchant_payment_token,
            PaymentMethod::ACH => (bool)$account->merchant_ach_account,
            PaymentMethod::AccountCredit => (bool)$account->account_credit > 0,
            default => true
        };
    }


}
