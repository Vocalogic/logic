<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Core\ACL;
use App\Enums\Core\CommKey;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{

    /**
     * Show users
     * @return View
     */
    public function index(): View
    {
        return view('admin.users.index');
    }

    /**
     * Show User Editor
     * @param User $user
     * @return View
     */
    public function show(User $user): View
    {
        return view('admin.users.show')->with('u', $user);
    }

    /**
     * Update a user.
     * @param User    $user
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(User $user, Request $request): RedirectResponse
    {
        $user->update([
            'name'              => $request->name,
            'email'             => $request->email,
            'color'             => $request->color,
            'phone'             => onlyNumbers($request->phone),
            'goal_monthly'      => $request->goal_monthly,
            'goal_quarterly'    => $request->goal_quarterly ?: $request->goal_monthly * 3,
            'goal_f_monthly'    => $request->goal_f_monthly,
            'goal_f_quarterly'  => $request->goal_f_quarterly ?: $request->goal_f_monthly * 3,
            'agent_comm_spiff'  => onlyNumbers($request->agent_comm_spiff),
            'agent_comm_mrc'    => onlyNumbers($request->agent_comm_mrc),
            'acl'               => $request->acl,
            'requires_approval' => (bool)$request->requires_approval
        ]);
        return redirect()->to("/admin/users");
    }


    /**
     * Send Forgot Password
     * @param User $user
     * @return string[]
     */
    public function resetUser(User $user): array
    {
        $user->sendForgotPassword();
        return ['callback' => "reload"];
    }

    /**
     * Create and Send Password Reset
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $u = (new User)->create([
            'account_id'       => 1,
            'name'             => $request->name,
            'email'            => $request->email,
            'password'         => bcrypt("X-" . mt_rand(51231, 555233)),
            'goal_monthly'     => $request->goal_monthly,
            'goal_quarterly'   => $request->goal_quarterly ?: $request->goal_monthly * 3,
            'goal_f_monthly'   => $request->goal_f_monthly,
            'goal_f_quarterly' => $request->goal_f_quarterly ?: $request->goal_f_monthly * 3,
            'agent_comm_spiff' => onlyNumbers($request->agent_comm_spiff),
            'agent_comm_mrc'   => onlyNumbers($request->agent_comm_mrc),
            'acl'              => $request->acl
        ]);
        $u->sendForgotPassword();
        return redirect()->to("/admin/users")->with('message', "Password Reset Request Sent..");
    }

    /**
     * Toggle Mode
     * @return RedirectResponse
     */
    public function toggleMode(): RedirectResponse
    {

        if (isset(user()->preferences['mode']))
        {
            $mode = user()->preference('mode') == 'dark' ? 'light' : 'dark';
        }
        else
        {
            $mode = 'dark';
        }
        user()->preference('mode', $mode);
        return redirect()->back();
    }

    /**
     * Return to admin from shadowing a user.
     * @return RedirectResponse
     */
    public function unshadow(): RedirectResponse
    {
        if (!session(CommKey::AdminUidFromShadow->value)) abort(404);
        $uid = session(CommKey::AdminUidFromShadow->value);
        auth()->logout();
        session()->flush();
        auth()->loginUsingId($uid);
        return redirect()->to("/");
    }

}
