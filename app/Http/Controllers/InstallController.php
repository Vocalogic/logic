<?php

namespace App\Http\Controllers;

use App\Enums\Core\ACL;
use App\Exceptions\LogicException;
use App\Models\Account;
use App\Models\EmailTemplate;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InstallController extends Controller
{
    /**
     * Show default installer landing page.
     * @return View
     */
    public function index(): View
    {
        if (setting('brand.name')) abort(404);
        return view('install.index');
    }

    /**
     * Create admin user and build initial account for admin
     * @param Request $request
     * @return RedirectResponse
     * @throws LogicException
     */
    public function store(Request $request) : RedirectResponse
    {
        $request->validate([
            'name'     => 'required',
            'company'  => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);
        if ($request->password != $request->password2)
        {
            throw new LogicException("Passwords do not match. Please try again.");
        }
        // Create account
        $account = (new Account)->create([
            'name'   => $request->company,
            'active' => 1
        ]);

        // Create admin user.
        $user = (new User)->create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => bcrypt($request->password),
            'account_id' => $account->id,
            'acl'        => ACL::ADMIN->value,
            'active'     => 1,
        ]);


        $user->update(['account_id' => $account->id]);
        $user->authorizeIp();
        setting('brand.name', $request->company);
        setting('brand.url', env('APP_URL'));
        EmailTemplate::placeholders();
        auth()->loginUsingId($user->id);
        return redirect()->to("/");
    }

}
