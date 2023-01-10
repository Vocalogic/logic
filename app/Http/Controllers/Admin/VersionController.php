<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Core\CommKey;
use App\Exceptions\LogicException;
use App\Http\Controllers\Controller;
use App\Operations\API\Control;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;

class VersionController extends Controller
{
    /**
     * Get a list of version controls
     * @return View|RedirectResponse
     * @throws GuzzleException
     */
    public function index() : View|RedirectResponse
    {
        $c = new Control();
        try
        {
            $versions = $c->getVersions();
        } catch(Exception) // Is control down or unreachable?
        {
            return redirect()->to("/admin");
        }
        return view('admin.versions.index')->with('versions', $versions);
    }

    /**
     * Upgrade Logic
     * @return string[]
     */
    public function upgrade():array
    {
        cache([CommKey::GlobalUpgradeTrigger->value => true], CommKey::GlobalUpgradeTrigger->getLifeTime());
        return ['callback' => "redirect:/admin"];
    }

}
