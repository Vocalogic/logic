<?php

namespace App\Http\Controllers\API;

use App\Exceptions\LogicException;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\User;
use FilesystemIterator;
use RecursiveIteratorIterator;
use Symfony\Component\Finder\Iterator\RecursiveDirectoryIterator;

class EnterpriseController extends Controller
{
    /**
     * This API should only accept calls for Logic Enterprise Manager.
     * For Community edition this API is not used, and should be restricted
     * to only IPs of the enterprise manager.
     *
     * WARNING: If you fork this project and change this, you could risk
     * exposing sensitive data. USE AT YOUR OWN RISK!
     * @var array|string[]
     */
    public array $entSource = ['69.61.3.92', '127.0.0.1'];

    /**
     * Validate all hits to this API to only allow from allowed sources.
     * @return void
     * @throws LogicException
     */
    private function validateSource(): void
    {
        $req = app('request');
        if (!in_array($req->ip(), $this->entSource))
        {
            throw new LogicException("Unauthorized.");
        }
    }

    /**
     * Return current usage to enterprise manager so customers can
     * see how much they are using vs which plan they are on.
     * @return array
     * @throws LogicException
     */
    public function getUsage(): array
    {
        $this->validateSource();
        return [
            'users'    => User::where('active', true)->where('account_id', 1)->count(),
            'accounts' => Account::where('active', true)->count() - 1, // don't include admin account
            'space'    => $this->getSpaceInGB()
        ];
    }

    /**
     * Get space used in storage.
     * @return float
     */
    private function getSpaceInGB(): float
    {
        $path = storage_path();
        $bytestotal = 0;
        $path = realpath($path);
        if ($path !== false && $path != '' && file_exists($path))
        {
            foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path,
                FilesystemIterator::SKIP_DOTS)) as $object)
            {
                $bytestotal += $object->getSize();
            }
        }
        return round($bytestotal / 1024 / 1024 / 1024, 2);
    }

}
