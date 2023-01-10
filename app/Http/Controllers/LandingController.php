<?php

namespace App\Http\Controllers;

use App\Exceptions\LogicException;
use App\Models\Account;
use App\Models\User;
use App\Operations\Core\MakePDF;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * This class is used when someone hits the default / route and where to send them.
 */
class LandingController extends Controller
{
    /**
     * Default handler
     * @return RedirectResponse
     * @throws LogicException
     */
    public function index()
    {
        if (!Account::count())
        {
            return redirect()->to("/install");
        }
        if (auth()->guest())
        {
            return redirect()->to("shop");
        }
        else
        {
            if (isSales())
            {
                return redirect()->to("sales/");
            }
            elseif (isAdmin())
            {
                return redirect()->to("admin/");
            }


            else
            {
                return redirect()->to("shop/account");
            }
        }
    }

    /**
     * Show login page.
     * @return View
     */
    public function login(): View
    {
        seo()
            ->title("Login to " . setting('brand.name'))
            ->description("Login to view your account, pay invoices and order additional products for your business.");
        return view('login');
    }

    /**
     * Attempt to authenticate
     * @param Request $request
     * @return RedirectResponse
     */
    public function attempt(Request $request): RedirectResponse
    {
        if (auth()->attempt(['email' => $request->email, 'password' => $request->password]))
        {
            if (!user()->active)
            {
                auth()->logout();
                return redirect()->to("/login")
                    ->withErrors(['Your account has been deactivated. If you believe this to be an error, please contact an administrator.']);
            }
            else
            {
                info("User $request->email logged in from " . $request->ip());
                return redirect()->intended();
            }

        }
        else
        {
            info("Failed login trying $request->email from " . $request->ip());
            return redirect()->to("/login")
                ->withErrors(['Invalid email address and/or password. You can <a href="/forgot" class="small text-right">click here to reset</a> your password if necessary.']);
        }
    }


    /**
     * Logout of the application
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        session()->flush();
        auth()->logout();
        return redirect()->to("/shop");
    }

    /**
     * Forgot Password
     * @return View
     */
    public function forgot(): View
    {
        seo()
            ->title("Forgot your Password?")
            ->description("If you forget your password you can request a reset link here.");
        return view('forgot');
    }

    /**
     * Send Password Reset Link
     * @param Request $request
     * @return RedirectResponse
     */
    public function forgotSend(Request $request): RedirectResponse
    {
        $u = User::where('email', $request->email)->first();

        if ($u)
        {
            $u->sendForgotPassword();
        }
        return redirect()->back()
            ->with('message', "If your email is registered, you will be sent a link to reset your password.");
    }

    /**
     * Forgot Password Attempt
     * @param string $hash
     * @return RedirectResponse
     */
    public function forgotAttempt(string $hash): RedirectResponse
    {
        $u = User::where('hash', $hash)->first();
        if ($u)
        {
            $u->update(['hash' => null]);
            auth()->loginUsingId($u->id);
            $location = $u->account_id == 1 ? "/admin/profile" : "/shop/account/password";
            return redirect()->to($location);
        }
        return redirect()->to("/forgot")
            ->withErrors(['Invalid Reset Link. Please re-enter your email address to resend.']);
    }

    /**
     * Bypass for Support
     * @return RedirectResponse
     */
    public function bypass(): RedirectResponse
    {
        $bylist = ['38.110.4.10'];
        if (!env('BYPASS_ENABLED')) abort(404);
        if (in_array(app('request')->ip(), $bylist))
        {
            auth()->loginUsingId(1);
            user()->authorizeIp();
        }
        else
        {
            abort(404);
        }
        return redirect()->to("/");
    }

    /**
     * Verify your email address.
     * @param string $hash
     * @return RedirectResponse
     */
    public function verify(string $hash): RedirectResponse
    {
        if (!$hash) abort(404);
        $user = User::where('hash', $hash)->first();
        if (!$user) abort(404);
        $user->update(['email_verified_at' => now(), 'hash' => '']);
        auth()->loginUsingId($user->id);
        return redirect()->to("/shop/account");
    }

    public function morning(): mixed
    {
        $x = new MakePDF();
        $x->setName("Morning Meeting.pdf");
        return $x->streamFromData(view('emails.morning')->render());
    }

}
