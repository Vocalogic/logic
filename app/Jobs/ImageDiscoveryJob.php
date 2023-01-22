<?php

namespace App\Jobs;

use App\Enums\Files\FileType;
use App\Models\Account;
use App\Operations\Core\LoFileHandler;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImageDiscoveryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Account $account;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $files = ['favicon.ico', 'favicon.jpg', 'favicon.gif'];
        $found = false;
        $image = null;
        $site = $this->account->website;
        if (!$site) return; // No website, not sure how we made it here.
        if (!preg_match("/http/i", $site))
        {
            $site = "https://" . $site;
        }
        foreach ($files as $file)
        {
            if ($found) continue;
            try
            {
                $img = file_get_contents($site . "/$file");
            } catch (Exception)
            {
                info("Couldn't find favicon at $site/$file.. Keep going.");
                continue;
            }
            if ($img)
            {
                $image = $img;
                $found = true;
            }
        }
        // No base favicon found. Let's look at the index page for the icon.
        try
        {
            $html = explode("\n", file_get_contents($site));
        } catch (Exception)
        {
            return;
        }
        $found = false;
        foreach ($html as $line)
        {
            if ($found) continue;
            if (preg_match("/favicon/i", $line))
            {
                // Try to get a file here.
                preg_match_all("/href=\"(.*)\"/i", $line, $match);
                if (isset($match[1][0]))
                {
                    // Got a possible file?
                    try
                    {
                        $img = file_get_contents($site . $match[1][0]);
                    } catch (Exception)
                    {
                        continue;
                    }
                    if ($img)
                    {
                        $image = $img;
                        $found = true;
                    }
                }
            }
        }
        if ($image)
        {
            // we got an image; process it
            $base = base64_encode($image);
            $x = new LoFileHandler();
            $file = $x->create('favicon.png', FileType::Image, $this->id, $base, 'image/png');
            $this->account->update(['logo_id' => $file->id]);
        }
    }
}
