<?php

namespace App\Enums\Core;

enum ACL: string
{
    case MANAGER = 'MANAGER';
    case USER = 'USER';
    case ADMIN = 'ADMIN';
    case SALES = 'SALES';

    /**
     * Get human representation of ACL
     * @return string
     */
    public function getHuman(): string
    {
        return match ($this)
        {
            self::ADMIN => "Account Administrator",
            self::MANAGER => "System Manager",
            self::USER => "Standard User",
            self::SALES => "Sales Associate"
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
