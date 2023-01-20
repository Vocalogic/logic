<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Core\CommKey;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AccountUserController extends Controller
{

    /**
     * Show Account User Modal
     * @param Account $account
     * @param User    $user
     * @return View
     */
    public function show(Account $account, User $user): View
    {
        return view('admin.accounts.users.show')->with('account', $account)->with('u', $user);
    }

    /**
     * Create a new user.
     * @param Account $account
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Account $account, Request $request): RedirectResponse
    {
        $request->validate([
            'name'  => 'required',
            'email' => "required|email",
            'acl'   => 'required'
        ]);
        $u = (new User)->create([
            'name'       => $request->name,
            'email'      => $request->email,
            'acl'        => $request->acl,
            'password'   => bcrypt("PW-" . mt_rand(52345, 23152423)),
            'account_id' => $account->id,
            'active'     => 1,
        ]);
        $u->sendForgotPassword();
        return redirect()->back();
    }

    /**
     * Update a User.
     * @param Account $account
     * @param User    $user
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Account $account, User $user, Request $request): RedirectResponse
    {
        $user->update($request->all());
        return redirect()->back();
    }

    /**
     * Send Reset Password Link
     * @param Account $account
     * @param User    $user
     * @return string[]
     */
    public function resetUser(Account $account, User $user): array
    {
        $user->sendForgotPassword();
        return ['callback' => "reload"];
    }

    /**
     * Deactivate a user.
     * @param Account $account
     * @param User    $user
     * @return string[]
     */
    public function destroy(Account $account, User $user): array
    {
        $user->update(['active' => !$user->active]);
        return ['callback' => "reload"];
    }

    /**
     * Shadow other user
     * @param Account $account
     * @param User    $user
     * @return RedirectResponse
     */
    public function shadow(Account $account, User $user): RedirectResponse
    {
        $uid = user()->id;
        auth()->logout();
        session()->flush();
        auth()->loginUsingId($user->id);
        session([CommKey::AdminUidFromShadow->value => $uid]);
        return redirect()->to("/shop");
    }

}
