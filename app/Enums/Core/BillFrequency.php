<?php

namespace App\Enums\Core;

/**
 * Define the different types of billing cycles.
 */
enum BillFrequency: string
{
    case Monthly = 'MONTHLY';
    case Quarterly = 'QUARTERLY';
    case Annually = 'ANNUALLY';

    /**
     * Get human selectable name.
     * @return string
     */
    public function getHuman(): string
    {
        return match ($this)
        {
            self::Monthly => 'Monthly',
            self::Quarterly => 'Quarterly',
            self::Annually => 'Annually'
        };
    }

    /**
     * Get short for like "p/yr, p/mo, etc
     * @return string
     */
    public function getHumanShort(): string
    {
        return match ($this)
        {
            self::Monthly => 'mo',
            self::Quarterly => 'qtr',
            self::Annually => 'yr'
        };
    }

    /**
     * Get split total based on total and number of payments.
     * @param float    $total
     * @param int|null $numPayments
     * @return int
     */
    public function splitTotal(float $total, ?int $numPayments) : int
    {
        if ($numPayments <= 0) return $total;
        return round($total / $numPayments);
    }

    /**
     * Get number of months to increase billing if selected.
     * @return int
     */
    public function getMonths(): int
    {
        return match ($this)
        {
            self::Monthly => 1,
            self::Quarterly => 3,
            self::Annually => 12
        };
    }

    /**
     * Get a selectable array.
     * @return array
     */
    static public function getSelectable(): array
    {
        $data = [];
        foreach (self::cases() as $case)
        {
            $data[$case->value] = $case->getHuman();
        }
        return $data;
    }

}
