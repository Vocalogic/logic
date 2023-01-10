<?php

namespace App\Enums\Core;

enum MeetingType: string
{
    case CustomerLocation = "CUSTOMER_LOCATION";
    case CompanyLocation = "COMPANY_LOCATION";
    case PhoneCall = "PHONE_CALL";
    case WebConference = "WEB_CONFERENCE";

    /**
     * Define the default meeting type if we don't know from
     * an import.
     * @return MeetingType
     */
    static public function defaultType(): MeetingType
    {
        return self::PhoneCall;
    }

    /**
     * Get Human Readable
     * @return string
     */
    public function getHuman(): string
    {
        return match ($this)
        {
            self::CustomerLocation => "Customer Location",
            self::CompanyLocation => setting('brand.name') . " Office",
            self::PhoneCall => "Phone Call",
            self::WebConference => "Web Conference"
        };
    }

    /**
     * Get selectable array for determining type of meeting.
     * @return array
     */
    static public function selectable(): array
    {
        $data = [];
        foreach (self::cases() as $case)
        {
            $data[$case->value] = $case->getHuman();
        }
        return $data;
    }

}
