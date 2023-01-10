<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Core\IntegrationRegistry;
use App\Http\Controllers\Controller;
use App\Models\Integration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IntegrationController extends Controller
{
    /**
     * Show list of integrations.
     * @return View
     */
    public function index(): View
    {
        Integration::init();
        return view('admin.integrations.index');
    }

    /**
     * Enable an integration
     * @param string $int
     * @return string[]
     */
    public function enable(string $int): array
    {
        $i = IntegrationRegistry::tryFrom($int);
        if (!$i) return ['callback' => 'reload'];
        // First disable all other of this type.
        $type = $i->getCategory();
        foreach (IntegrationRegistry::cases() as $case)
        {
            if ($case->getCategory() == $type)
            {
                Integration::where('ident', $case->value)->update(['enabled' => 0]);
            }
        }
        $integration = Integration::where('ident', $i->value)->first();
        $integration->update(['enabled' => true]);
        return ['callback' => "redirect:/admin/integrations/$int"];
    }

    /**
     * Disable an integration
     * @param string $int
     * @return string[]
     */
    public function disable(string $int): array
    {
        $i = IntegrationRegistry::tryFrom($int);
        if (!$i) return ['callback' => 'reload'];
        $integration = Integration::where('ident', $i->value)->first();
        $integration->update(['enabled' => false]);
        return ['callback' => 'redirect:/admin/integrations'];
    }

    /**
     * Show configuration for integration.
     * @param string $int
     * @return View
     */
    public function show(string $int): View
    {
        $i = IntegrationRegistry::tryFrom($int);
        if (!$i) return abort(404);
        $integration = Integration::where('ident', $i->value)->first();
        return view('admin.integrations.show')->with('integration', $integration);
    }

    /**
     * Update configuration settings
     * @param string  $int
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(string $int, Request $request): RedirectResponse
    {
        $i = IntegrationRegistry::tryFrom($int);
        if (!$i) abort(404);
        $integration = Integration::where('ident', $i->value)->first();
        $integration->pack($request);
        return redirect()->to("/admin/integrations");
    }



}
