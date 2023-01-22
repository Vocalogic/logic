<?php

namespace App\Jobs;

use App\Enums\Core\IntegrationType;
use App\Models\Activity;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DispatchChat implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Activity $activity;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Activity $activity)
    {
        $this->activity = $activity;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $channel = $this->activity->type->getChannel();
        if (!$channel) return; // No Channel for this particular type of message.
        try {
            getIntegration(IntegrationType::Chat)->connect()->send($channel, $this->activity->formatted);
        } catch(Exception $e)
        {
            info("Failed to send to chat channel - " . $e->getMessage());
        }
    }
}
