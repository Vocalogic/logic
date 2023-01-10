<?php

namespace App\Http\Controllers;

use App\Enums\Core\IntegrationRegistry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OAuthController extends Controller
{

    /**
     * Step 1: Authorize the app. This will be an inbound route that will
     * generate a redirect to the 3rd party service for authorization.
     * The callback url will be used to process the next section
     * @param string $service
     * @return RedirectResponse
     */
    public function auth(string $service): RedirectResponse
    {
        $integration = IntegrationRegistry::tryFrom($service);
        if (!$integration) abort(404);
        return redirect()->to($integration->connect()->getOauthRedirect());
    }

    /**
     * After the user authorizes the app, we will save our keys and redirect back to the
     * dashboard.
     * @param string  $service String representation of the integration (registry)
     * @param Request $request
     * @return RedirectResponse
     */
    public function callback(string $service, Request $request) : RedirectResponse
    {
        $integration = IntegrationRegistry::tryFrom($service);
        if (!$integration) abort(404);
        $integration->connect()->processCallback($request);
        return redirect()->to("/");
    }

}
