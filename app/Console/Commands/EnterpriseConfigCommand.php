<?php

namespace App\Console\Commands;

use App\Operations\API\LogicEnterprise;
use Illuminate\Console\Command;

class EnterpriseConfigCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enterprise:config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Used for Logic Enterprise Customers for Configuration';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // This command will attempt to self-install for customers that are using Logic Enterprise.
        // This command can only be used once during installation and will 401 after the first hit.
        $le = new LogicEnterprise();
        try {
            $config = $le->getConfig();
        } catch(\Exception $e)
        {
            $this->error($e->getMessage());
            return;
        }
        if ($config->success == true)
        {
            file_put_contents(base_path() . "/" . ".env", $config->env);
            setting('brand.license', $config->license);
        }
        else $this->error($config->reason);
    }
}
