<?php

namespace App\Operations\Core;

use App\Enums\Core\CommKey;
use App\Enums\Core\InvoiceStatus;
use App\Enums\Core\MetricType;
use App\Models\Account;
use App\Models\BillItem;
use App\Models\Invoice;
use App\Models\Metric;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;


class GraphSeries
{
    public Request    $request;
    public MetricType $type;
    // Series Calculation Requests
    public int      $days       = 0;                                                                                                                                                   // Specify Time in Days
    public int      $weeks      = 0;                                                                                                                                                   // Specify time in weeks
    public int      $months     = 0;                                                                                                                                                   // Specify time in months
    public string   $tally;                                                                                                                                                            // Tally sum, average or min
    public string   $seriesType;                                                                                                                                                       // Series Type is which method to use below
    public Carbon   $start;                                                                                                                                                            // Start date
    public Carbon   $end;                                                                                                                                                              // End Date
    public ?Account $account;                                                                                                                                                          // Associated Account if needed.
    public array    $blocks     = [];                                                                                                                                                  // Blocks of time for week/months
    public array    $addMetrics = [];                                                                                                                                                  // Additional Metric Series
    public array    $colors     = [];                                                                                                                                                  // Color Indexes
    public bool     $diff       = false;                                                                                                                                               // Diffferential not total?
    public array    $series     = [];                                                                                                                                                  // Series Data Storage
    public array    $options    = [];                                                                                                                                                  // Base Object Construction
    public ?string  $detail;                                                                                                                                                           // Define Detail match if necessary.

    public int    $chartHeight = 300;                                                                               // Chart Definitions
    public bool   $showToolBar = false;
    public string $yAxis       = '';
    public string $xAxis       = '';
    public array  $labels      = [];

    /**
     * Setup Series Renderer
     * @param string  $type
     * @param Request $request
     */
    public function __construct(string $type, Request $request)
    {
        $this->type = MetricType::tryFrom($type);
        $this->request = $request;
        $this->seriesType = $request->seriesType ?: 'timeSeries';
        $this->start = $request->start ? Carbon::parse($request->start) : now()->subWeek();
        $this->end = $request->end ? Carbon::parse($request->end) : now();
        $this->tally = $request->tally ?: 'average';
        $this->diff = (bool)$request->diff;
        $this->detail = $request->detail;
        if ($request->days)
        {
            $this->days = (int)$request->days;
        }
        if ($request->weeks)
        {
            $this->weeks = (int)$request->weeks;
        }
        if ($request->months)
        {
            $this->months = (int)$request->months;
        }
        if ($request->account)
        {
            $this->account = Account::find($request->account);
        }
        else $this->account = null;

        if ($request->with)
        {
            $mets = explode(",", $request->with);
            foreach ($mets as $met)
            {
                $this->addMetrics[] = MetricType::tryFrom($met);
            }
        }
        $this->colors = [
            'var(--chart-color1)',
            'var(--chart-color2)',
            'var(--chart-color3)',
            'var(--chart-color4)',
            'var(--chart-color5)'
        ];
    }

    /**
     * Get Array or Object for Series
     * @return mixed
     */
    public function run(): mixed
    {
        $this->build();

        $this->buildTimeBlocks();
        if (!$this->request->fn)
        {
            $fn = $this->seriesType;
        }
        else $fn = $this->request->fn;
        $this->series = $this->$fn($this->type);

        // Finally we will apply our series to our existing options object.
        $this->options['series'] = $this->series;
        if (!empty($this->labels))
        {
            $this->options['labels'] = $this->labels;
        }
        return $this->options;
    }

    /**
     * Basic Time Series based on metric range
     * @param MetricType $type
     * @return array[]
     */
    private function timeSeries(MetricType $type): array
    {
        $plots = [];
        $series = [];
        $colorIndex = 0;
        foreach ($this->blocks as $block)
        {
            $entries = _metrics($block, $this->getEndPeriod($block), $this->account, $type, false, $this->detail);
            $data = $this->getTotalFromMetrics($entries);
            if ($type->isMoney()) $data = moneyFormat($data, false);
            $plots[] = (object)[
                'x' => $block->getTimestampMs(),
                'y' => $data
            ];
        }
        $series[] = [
            'name'  => $type->getSeriesName(),
            'data'  => $plots,
            'color' => $this->colors[$colorIndex],
            'type'  => $this->request->s0 ?: 'line'
        ];

        if ($this->addMetrics)
        {
            foreach ($this->addMetrics as $metric) // Iterate each metric type
            {
                $colorIndex++;
                $plots = [];
                foreach ($this->blocks as $block) // iterate for each block of time
                {
                    $entries = _metrics($block, $this->getEndPeriod($block), $this->account, $metric, $this->diff,
                        $this->detail);
                    $data = $this->getTotalFromMetrics($entries);
                    if ($type->isMoney()) $data = moneyFormat($data, false);
                    $plots[] = (object)[
                        'x' => $block->getTimestampMs(),
                        'y' => $data
                    ];
                } // fe block
                $stype = sprintf("s%d", $colorIndex);
                $series[] = [
                    'name'  => $metric->getSeriesName(),
                    'data'  => $plots,
                    'color' => $this->colors[$colorIndex],
                    'type'  => $this->request->{$stype} ?: 'line'
                ];
            } // fe metric type
        }
        return $series;
    }

    /**
     * Get a minified dataset for a sparkline
     * @param MetricType $type
     * @return array
     */
    public function sparkSeries(MetricType $type): array
    {
        $plots = [];
        foreach ($this->blocks as $block)
        {
            $entries = _metrics($block, $this->getEndPeriod($block), $this->account, $type, $this->diff, $this->detail);
            $data = $this->getTotalFromMetrics($entries);
            if ($type->isMoney()) $data = moneyFormat($data, false);
            $plots[] = $data;
        }
        return [
            [
                'name'  => $type->getSeriesName(),
                'data'  => $plots,
                'color' => $this->request->color ? $this->colors[$this->request->color] : $this->colors[0]
            ]
        ];
    }


    /**
     * Build Timeblocks for Start metric pulls
     * @return void
     */
    private function buildTimeBlocks()
    {
        if ($this->days)
        {
            foreach (range(1, $this->days) as $day)
            {
                $this->blocks[] = now()->startOfDay()->subDays($day);
            }
        }
        if ($this->weeks)
        {
            foreach (range(0, $this->weeks) as $week)
            {
                $this->blocks[] = now()->startOfWeek()->subWeeks($week);
            }
        }
        if ($this->months)
        {
            foreach (range(0, $this->months) as $month)
            {
                $this->blocks[] = now()->startOfMonth()->subMonths($month);
            }
        }
    }

    /**
     * Get end period for start block
     * @param Carbon $start
     * @return Carbon
     */
    private function getEndPeriod(Carbon $start): Carbon
    {
        if ($this->days > 0)
        {
            return $start->copy()->endOfDay();
        }
        elseif ($this->weeks > 0)
        {
            return $start->copy()->endOfWeek();
        }
        elseif ($this->months > 0)
        {
            return $start->copy()->endOfMonth();
        }
        return $start->copy()->startOfDay();
    }

    /**
     * Return a total entry value based on aggregation method
     * @param Collection $entries
     * @return int|float
     */
    public function getTotalFromMetrics(Collection $entries): int|float
    {
        if ($this->tally == 'average')
        {
            return $entries->average('value') ?: 0;
        }
        elseif ($this->tally == 'sum') return $entries->sum('value') ?: 0;
        elseif ($this->tally == 'max') return $entries->max('value') ?: 0;
        else return $entries->sum('value') ?: 0;
    }

    /**
     * Get a live breakout of invoices by status type
     * @return array
     */
    public function getInvoiceStatusPie(): array
    {
        $invoices = Invoice::whereIn('status',
            [InvoiceStatus::PARTIAL, InvoiceStatus::SENT, InvoiceStatus::DRAFT])
            ->get();
        $pastDue = 0;
        $openNotPD = 0;
        $partial = 0;
        $draft = 0;
        $total = $invoices->count();
        foreach ($invoices as $invoice)
        {
            if ($invoice->isPastDue)
            {
                $pastDue++;
            }
            elseif ($invoice->status == InvoiceStatus::DRAFT) $draft++;
            elseif ($invoice->status == InvoiceStatus::SENT) $openNotPD++;
            elseif ($invoice->status == InvoiceStatus::PARTIAL) $partial++;
        }
        $pastDuePerc = $total > 0 ? round($pastDue / $total * 100) : 0;
        $openPerc = $total > 0 ? round($openNotPD / $total * 100) : 0;
        $partPerc = $total > 0 ? round($partial / $total * 100) : 0;
        $draftPerc = $total > 0 ? round($draft / $total * 100) : 0;
        $this->labels = ['Outstanding', 'Draft', 'Past Due', 'Partial Payment'];
        $this->series = [$openPerc, $draftPerc, $pastDuePerc, $partPerc];
        return $this->series;
    }

    /**
     * This function will get the graph for the account that shows how much was set in MRR
     * for a given month vs how much was actually billed. Will show customers paying more
     * than what they were originally intending, etc.
     * @return array
     */
    public function getInvoicedMRRDiff(): array
    {
        // We should take the account_id, and months to determine how far to go back.
        // We will use _metrics to figure out historical MRR.
        $this->options['stroke'] = (object)[
            'width'     => [5, 7, 5],
            'curve'     => 'straight',
            'dashArray' => [0, 8, 5]
        ];
        $account = Account::find($this->request->account);
        if (cache(CommKey::AccountMRRCache->value))
        {
            $data = cache(CommKey::AccountMRRCache->value);
            if (isset($data[$account->id]))
            {
                $this->series = $data[$account->id];
                return $this->series;
            }
        }

        $mrrPlots = [];
        $invoicePlots = [];
        foreach (range(0, $this->request->months ?? 6) as $month)
        {
            $start = now()->subMonths($month)->startOfMonth();
            $end = now()->subMonths($month)->endOfMonth();
            $mrr = 0;
            $day = $start->copy();
            while (true)
            {
                $mrrMetric = _metric($day, $account, MetricType::AccountMRR);
                $mrrMetric = $mrrMetric->first();
                if ($mrrMetric && $mrrMetric->value > 0)
                {
                    $mrr = $mrrMetric->value;
                    break;
                }
                $day->addDay();
                if ($day >= $end) break;
            }
            $mTotal = 0;
            foreach ($account->invoices()->whereBetween('created_at', [$start, $end])
                         ->where('status', '!=', InvoiceStatus::DRAFT)
                         ->get() as $invoice)
            {
                $mTotal += $invoice->total;
            }

            // We have mrr and total.. add dataplot
            $mrrPlots[] = (object)[
                'x' => $start->getTimestampMs(),
                'y' => moneyFormat($mrr, false)
            ];

            $invoicePlots[] = (object)[
                'x' => $start->getTimestampMs(),
                'y' => moneyFormat($mTotal, false)
            ];
        }

        $this->series[] = [
            'name'  => 'MRR',
            'data'  => $mrrPlots,
            'color' => $this->colors[0],
            'type'  => 'line'
        ];
        $this->series[] = [
            'name'  => 'Total Invoiced',
            'data'  => $invoicePlots,
            'color' => $this->colors[3],
            'type'  => 'line'
        ];


        $cacheValue = cache(CommKey::AccountMRRCache->value);
        if (!$cacheValue) $cacheValue = [];
        $cacheValue[$account->id] = $this->series;
        cache([CommKey::AccountMRRCache->value => $cacheValue], CommKey::AccountMRRCache->getLifeTime());
        return $this->series;
    }

    /**
     * Get a timeseries graph of what each product and service has been
     * sold for over the company history.
     * @return array
     */
    public function getBillItemPriceChart(): array
    {
        $item = BillItem::find($this->request->item);
        $soldPlots = [];
        foreach(Invoice::all() as $invoice)
        {
            foreach($invoice->items as $ii)
            {
                if ($ii->bill_item_id == $item->id)
                {
                    $soldPlots[] = [
                        'x' => $ii->created_at->getTimestampMs(),
                        'y' => moneyFormat($ii->price, false)
                    ];
                }
            }
        }
        $this->yAxis = "$item->name";
        $this->series[] = [
            'name' => $item->name,
            'data' => $soldPlots,
            'color' => $this->colors[0],
            'type' => 'area'
        ];
        // Override the yaxis
        $this->options['yaxis'] = (object)[
                'title' => (object)[
                    'text' => $this->yAxis
                ]
            ];
        return $this->series;
    }



    /**
     * Get Account Rank in MRR.
     * @return array
     */
    public function getAccountRankMRR(): array
    {
        $totalMrr = 0;
        $thisAccount = Account::find($this->request->account);
        foreach (Account::where('active', true)->get() as $account)
        {
            $totalMrr += $account->mrr;
        }
        // How does this account compare?
        $mrr = $thisAccount->mrr;
        $percOfTotal = round(($mrr / $totalMrr) * 100);
        $this->options = [
            'chart'       => (object)[
                'height' => 350,
                'type'   => 'radialBar',
            ],
            'plotOptions' => (object)[
                'radialBar' => (object)[
                    'hollow' => (object)[
                        'size' => '70%'
                    ]
                ]
            ],
        ];
        $rank = $this->account->rank;
        $this->labels[] = "#$rank in Global MRR";
        $this->series[] = $percOfTotal;
        return $this->series;
    }


    /**
     * Get Account Rank in Total Invoiced Amount
     * @return array
     */
    public function getAccountRankTotal(): array
    {
        $total = 0;
        $ttlBlock = [];
        $thisAccount = Account::find($this->request->account);
        foreach (Account::where('active', true)->get() as $account)
        {
            $ttlBlock[$account->id] = 0;
            foreach($account->invoices as $invoice)
            {
                $ttlBlock[$account->id] += $invoice->total;
                $total += $invoice->total;
            }

        }
        // How does this account compare?
        $accTotal = 0;
        foreach ($thisAccount->invoices as $invoice)
        {
            $accTotal += $invoice->total;
        }
        $percOfTotal = round(($accTotal / $total) * 100);
        $this->options = [
            'chart'       => (object)[
                'height' => 350,
                'type'   => 'radialBar',
            ],
            'plotOptions' => (object)[
                'radialBar' => (object)[
                    'hollow' => (object)[
                        'size' => '70%'
                    ]
                ]
            ],
        ];
        $rank = $this->account->rankTotal;
        $this->labels[] = "#$rank in Invoiced Total";
        $this->series[] = $percOfTotal;
        return $this->series;
    }

    /**
     * Set chart structures
     * @return void
     */
    public function build(): void
    {
        if ($this->seriesType == 'sparkSeries')
        {
            $this->chartHeight = 50;
            $this->options = [
                'chart'  => (object)[
                    'type'      => 'line',
                    'height'    => $this->chartHeight,
                    'sparkline' => (object)[
                        'enabled' => true
                    ]
                ],
                'series' => [],
                'nodata' => (object)[
                    'text' => 'Loading..'
                ],
                'grid'   => (object)[
                    'xaxis' => (object)[
                        'lines' => (object)[
                            'show' => false
                        ]
                    ]
                ],
                'stroke' => (object)[
                    'width'  => 3,
                    'curve'  => 'smooth',
                    'colors' => ['var(--chart-color1)']
                ],
                'fill'   => (object)[
                    'type'     => 'gradient',
                    'gradient' => (object)[
                        'shade'            => 'dark',
                        'gradeitnToColors' => ["var(--chart-color2)"],
                        'shadeIntensity'   => 1,
                        'type'             => 'horizontal',
                        'opacityFrom'      => 1,
                        'opacityTo'        => 1,
                        'stops'            => [0, 50, 100],
                        'colorStops'       => []
                    ]
                ]

            ];
            return;
        } // If Spark

        if ($this->seriesType == 'donut')
        {
            $this->options = [
                'series' => [],
                'chart'  => (object)[
                    'type' => 'donut'
                ],
                'labels' => $this->labels,
                'colors' => $this->colors
            ];
            return;
        }


        $this->yAxis = $this->yAxis ?: $this->type->getSeriesName();
        $this->options = [

            'chart'      => (object)[
                'type'    => 'bar',
                'height'  => $this->chartHeight,
                'toolbar' => (object)[
                    'show' => $this->showToolBar,
                ]
            ],
            'series'     => [],
            'nodata'     => (object)[
                'text' => 'Loading..'
            ],
            'dataLabels' => (object)[
                'enabled' => true
            ],
            'yaxis'      => (object)[
                'title' => (object)[
                    'text' => $this->yAxis
                ]
            ],
            'xaxis'      => (object)[
                'title' => (object)[
                    'text' => $this->xAxis
                ]
            ]
        ];


    }

}
