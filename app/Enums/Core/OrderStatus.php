<?php

namespace App\Enums\Core;

enum OrderStatus: string
{
    case PendingVerification = 'Pending Verification';
    case PendingInvoicePayment = 'Pending Invoice Payment';
    case Verified = 'Verified';
    case InProgress = 'In Progress';
    case Provisioned = 'Service(s) Provisioned';
    case Shipped = 'Shipped';
    case Completed = 'Completed';
    case Cancelled = 'Cancelled';
    case OrderInvalidated = 'Order Invalidated';

    /**
     * Get help regarding the status of your order.
     * @return string
     */
    public function getHelp(): string
    {
        return match ($this)
        {
            self::PendingInvoicePayment => "Pending Payment",
            self::InProgress => "Order is being processed",
            self::Shipped => "Order shipped",
            self::Completed => "Order completed.",
            self::Verified => "Order Verified",
            default => "Unknown"
        };
    }


    /**
     * A normal order flow for orders.
     * @param bool $shipped
     * @return array
     */
    static public function getStatusList(bool $shipped = false): array
    {
        if ($shipped)
        {
            return [
                self::PendingInvoicePayment,
                self::Verified,
                self::InProgress,
                self::Shipped,
                self::Completed
            ];
        }
        else
        {
            return [
                self::PendingInvoicePayment,
                self::Verified,
                self::InProgress,
                self::Provisioned,
                self::Completed
            ];
        }


    }


}
