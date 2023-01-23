<?php

namespace App\Http\Livewire\Admin;

use App\Exceptions\LogicException;
use App\Models\Account;
use App\Operations\API\LogicPay\LPCore;
use App\Operations\Integrations\Merchant\LogicPay;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\Redirector;

class LogicPayComponent extends Component
{
    public         $listeners    = ['logicToken'];
    public string  $message      = "Awaiting New Payment Details..";
    public string  $messageColor = 'info';
    public Account $account;
    public string  $token;

    /**
     * Render Component (also spawns listener for the token)
     * @return View
     */
    public function render(): View
    {
        return view('admin.accounts.billing.logic_component');
    }

    /**
     * Receive Token from Emitter
     * @param string $token
     * @return void
     * @throws GuzzleException
     */
    public function logicToken(string $token): void
    {
        $this->message = "Attempting to Validate..";
        $this->token = $token;
        $this->attemptAuthorization();
    }

    /**
     * Attempt to Authorize $1.00 with this token.
     * @return mixed
     * @throws GuzzleException
     */
    private function attemptAuthorization(): mixed
    {
        $lp = new LogicPay();
        try
        {
            $result = $lp->addPaymentMethod($this->account, $this->token);
        } catch (LogicException $e)
        {
            $this->message = $e->getMessage();
            $this->messageColor = 'danger';
            return null;
        }
        $this->account->refresh();
        $this->message = "<span class='text-success'>Payment Authorized! ($result->authcode)</span>";
        if (user()->account->id > 1)
        {
            return redirect()->to("/shop/account/profile")->with('message', "Your payment method has been updated with ".setting('brand.name'). " Successfully!");
        }
        else
        {
            return redirect()->to("/admin/accounts/{$this->account->id}")
                ->with('message', "Payment Method Updated Successfully!");
        }
    }
}
