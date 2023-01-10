<?php

namespace App\Operations\Admin;

use App\Models\Lead;
use App\Models\Quote;
use App\Models\User;

class GoalStats
{
    public User $user;

    // Holders for recurring pulls for the same user.
    static public User   $sUser;
    static public object $sObj;


    /**
     * Instantiate based on user.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get users goals and metrics for easy graph rendering
     * @return object
     */
    public function get(): object
    {
        if (isset(self::$sUser) && $this->user === self::$sUser)
        {
            return self::$sObj;
        }
        $data = (object)[];
        // First lets get the users goals
        $data->selfMonthly = $this->user->goal_self_monthly;
        $data->selfQuarterly = $this->user->goal_self_quarterly;
        $data->monthly = $this->user->goal_monthly;
        $data->quarterly = $this->user->goal_quarterly;
        $data->fMonthly = $this->user->goal_f_monthly;
        $data->fQuarterly = $this->user->goal_f_quarterly;
        $data->soldMonth = 0;
        $data->soldQuarter = 0;
        $data->fMonth = 0;
        $data->fQuarter = 0;

        // Now lets get their actual conversions and forecasted
        foreach (Lead::with('quotes')->where('agent_id', $this->user->id)->get() as $lead)
        {
            // Quotes for this quarter
            foreach ($lead->quotes()
                         ->whereBetween('created_at', [now()->startOfQuarter(), now()->endOfQuarter()])
                         ->get() as $quote)
            {
                if ($quote->status == 'Executed')
                {
                    $data->soldQuarter += $quote->mrr;
                }
            }

            // Get forecasted
            if ($lead->forecast_date)
            {
                if ($lead->forecast_date >= now()->startOfQuarter() && $lead->forecast_date <= now()->endOfQuarter())
                {
                    $pQuote = $lead->quotes()->where('preferred', 1)->first();
                    if ($pQuote)
                    {
                        $data->fQuarter += $pQuote->mrr;
                    }
                }
            }

            foreach ($lead->quotes()
                         ->whereBetween('activated_on', [now()->startOfMonth(), now()->endOfMonth()])
                         ->get() as $quote)
            {
                if ($quote->status == 'Executed')
                {
                    $data->soldMonth += $quote->mrr;
                }
            }

            // Get forecasted
            if ($lead->forecast_date)
            {
                if ($lead->forecast_date >= now()->startOfMonth() && $lead->forecast_date <= now()->endOfMonth())
                {
                    $pQuote = $lead->quotes()->where('preferred', 1)->first();
                    if ($pQuote)
                    {
                        $data->fMonth += $pQuote->mrr;
                    }
                }
            }
        }


        self::$sUser = $this->user;
        self::$sObj = $data;
        return $data;
    }

    /**
     * Get monthly chart
     * @return object
     */
    static public function getMonthly(): string
    {

        $sdata = [];
        foreach (User::where('account_id', 1)->where('active', 1)->get() as $user)
        {
            $x = new self($user);
            $gobj = $x->get();
            $goals = (object)[
                'name'            => "Goal",
                'value'           => $gobj->monthly,
                'strokeWidth'     => 2,
                'strokeDashArray' => 2,
                'strokeColor'     => '#775DD0'
            ];
            $sdata[] = (object)[
                'x'     => $user->name,
                'y'     => $gobj->soldMonth,
                'goals' => [$goals],
            ];
        }
        $series = [
            (object)[
                'name' => 'Actual',
                'data' => $sdata
            ]
        ];
        $c = collect($series);
        return $c->toJson();
    }

    /**
     * Get monthly chart
     * @return object
     */
    static public function getQuarterly(): string
    {
        $sdata = [];
        foreach (User::where('account_id', 1)->where('active', 1)->get() as $user)
        {
            $x = new self($user);
            $gobj = $x->get();
            $goals = (object)[
                'name'            => "Goal",
                'value'           => $gobj->quarterly,
                'strokeWidth'     => 2,
                'strokeDashArray' => 2,
                'strokeColor'     => '#775DD0'
            ];
            $sdata[] = (object)[
                'x'     => $user->name,
                'y'     => $gobj->soldQuarter,
                'goals' => [$goals],
            ];
        }
        $series = [
            (object)[
                'name' => 'Actual',
                'data' => $sdata
            ]
        ];
        $c = collect($series);
        return $c->toJson();
    }

}
