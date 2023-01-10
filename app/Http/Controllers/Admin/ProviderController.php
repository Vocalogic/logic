<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Files\FileType;
use App\Enums\Files\MimeRegistry;
use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Operations\API\Control;
use App\Operations\Core\LoFileHandler;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProviderController extends Controller
{
    /**
     * Show all Activated Providers
     * @return View|RedirectResponse
     */
    public function index(): View|RedirectResponse
    {
        if (provider())
            return redirect()->to("/admin/providers/" . provider()->id);
        return view('admin.providers.index');
    }

    /**
     * Show a provider's settings
     * @param Provider $provider
     * @return View
     */
    public function show(Provider $provider): View
    {
        if (!$provider->enabled) abort(404);
        return view('admin.providers.show')->with('provider', $provider);
    }

    /**
     * Update Provider
     * @param Provider $provider
     * @param Request  $request
     * @return RedirectResponse
     */
    public function update(Provider $provider, Request $request): RedirectResponse
    {
        $provider->update([
            'client_id'     => $request->client_id,
            'client_secret' => $request->client_secret,
            'username'      => $request->username,
            'password'      => $request->get('password'),
            'lnp_email'     => $request->lnp_email,
            'territory'     => $request->territory
        ]);
        return redirect()->to("/admin/providers");
    }

    /**
     * Show all providers not enabled.
     * @return View
     * @throws GuzzleException
     */
    public function enableIndex(): View
    {
        $c = new Control();
        $providers = $c->getProviders();
        return view('admin.providers.enable')->with('providers', $providers);
    }


    /**
     * Enable a provider
     * @param int $id
     * @return RedirectResponse
     * @throws GuzzleException
     */
    public function enable(int $id): RedirectResponse
    {
        $c = new Control();
        $providers = $c->getProviders();
        $pro = new Provider();
        foreach ($providers as $provider)
        {
            if ($provider->id == $id)
            {
                $pro = (new Provider)->create([
                    'name'      => $provider->name,
                    'website'   => $provider->website,
                    'logo'      => '',
                    'enabled'   => 1,
                    'endpoint'  => $provider->endpoint,
                    'remote_id' => $provider->id
                ]);
                $lo = new LoFileHandler();
                $file = $lo->create($provider->name, FileType::Image, $pro->id, $provider->logo, 'image/png');
                $pro->update(['logo_id' => $file->id]);
            }
        }
        return redirect()->to("/admin/providers/$pro->id");
    }

}
