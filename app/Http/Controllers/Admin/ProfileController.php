<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\LogicException;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show user's admin profile
     * @return View
     */
    public function index(): View
    {
        return view('admin.profile.index');
    }

    /**
     * Update Profile
     * @param Request $request
     * @return RedirectResponse
     * @throws LogicException
     */
    public function update(Request $request): RedirectResponse
    {
        $u = user();
        $u->update([
            'goal_self_monthly'   => $request->goal_self_monthly,
            'goal_self_quarterly' => $request->goal_self_quarterly ?: $request->goal_self_monthly * 3,

            'name'                => $request->name,
            'phone'               => onlyNumbers($request->phone)
        ]);
        if ($request->password)
        {
            if ($request->password != $request->password2) throw new LogicException("Passwords do not match.");
            $u->update(['password' => bcrypt($request->password)]);
        }
        return redirect()->to("/");
    }


}
