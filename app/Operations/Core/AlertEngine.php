<?php

namespace App\Operations\Core;

use App\Enums\Core\AlertType;
use App\Enums\Core\ModuleRegistry;
use App\Operations\Admin\AdminAlerts;

/**
 * Handle Alerts for both admin and customers.
 * This class will also get modules and pull in related contextual alerts.
 */
class AlertEngine
{

    /**
     * Admin alerts
     * @var array|string[]
     */
    static public array $adminAlerts = [];

    /**
     * Customer Alerts (only to be shown to customers)
     * @var array
     */
    static public array $customerAlerts = [];


    /**
     * Build an alert object
     * @param AlertType $type
     * @param string    $title
     * @param string    $description
     * @param string    $action
     * @param string    $url
     * @return void
     */
    public function instanceAlert(
        AlertType $type,
        string $title,
        string $description,
        string $action,
        string $url
    ): object {
        return (object)[
            'type'        => $type,
            'title'       => $title,
            'description' => $description,
            'action'      => $action,
            'url'         => $url,
            'instance'    => true
        ];
    }

    /**
     * Generate a non-instanced widget with a table view.
     * @param AlertType $type
     * @param string    $title
     * @param int       $count
     * @param string    $description
     * @param string    $icon
     * @param array     $headers
     * @param array     $data
     * @return object
     */
    public function widgetAlert(
        AlertType $type,
        string $title,
        int $count,
        string $description,
        string $icon,
        array $headers,
        array $data
    ): object {
        return (object)[
            'type'        => $type,
            'title'       => $title,
            'count'       => $count,
            'description' => $description,
            'icon'        => $icon,
            'instance'    => false,
            'headers'     => $headers,
            'data'        => $data,

        ];
    }

    /**
     * Runs all requsted alert operations based on if admin or customer.
     *
     * @param bool $admin
     * @return array
     */
    static public function run(bool $admin = true): array
    {
        $alerts = [];
        if ($admin)
        {
            foreach (ModuleRegistry::cases() as $case)
            {
                if ($case->isEnabled())
                {
                    $path = sprintf("\\Modules\\%s\\Operations\\%sAlertEngine", $case->getName(), $case->getName());
                    if (!class_exists($path)) continue;
                    $x = new $path;
                    foreach ($x->init() as $alert)
                    {
                        $alerts[] = $alert;
                    }
                }
            }
            // Now Native.
            $x = new AdminAlerts();
            $temps = $x->collect();
            foreach ($temps as $alert)
            {
                $alerts[] = $alert;
            }
        }

        return $alerts;
    }


}
